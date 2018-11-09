<?php

namespace core\useCases\manage\Company;


use core\components\SettingsManager;
use core\entities\Company\Company;
use core\entities\Company\CompanyPhoto;
use core\helpers\FileHelper;
use core\repositories\Company\CompanyPhotoRepository;
use core\services\TransactionManager;
use core\useCases\BasePhotoService;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Yii;
use yii\imagine\Image;
use yii\web\UploadedFile;

class CompanyPhotoService extends BasePhotoService
{
    public $component = 'company';
    public $photoEntityClass = CompanyPhoto::class;

    public function __construct(CompanyPhotoRepository $repository, TransactionManager $transaction)
    {
        $this->repository = $repository;
        $this->sizes = [
            'small' => ['width' => Yii::$app->settings->get(SettingsManager::COMPANY_SMALL_SIZE)],
            'big' => ['width' => Yii::$app->settings->get(SettingsManager::COMPANY_BIG_SIZE)],
            'max' => ['width' => Yii::$app->settings->get(SettingsManager::COMPANY_MAX_SIZE)],
        ];

        parent::__construct($transaction);
    }

    public function saveLogo(Company $company, UploadedFile $file): void
    {
        $optimizerChain = OptimizerChainFactory::create();
        $name = $company->id . "-" . time() . "." . $file->extension;

        if (!$file->saveAs($company->logoPath() . '/or_' . $name)) {
            Yii::error("Ошибка сохранения логотипа компании.");
            throw new \DomainException("Ошибка сохранения логотипа компании.");
        }

        foreach ($this->sizes as $type => $item) {
            $width = isset($item['width']) && (int) $item['width'] ? (int) $item['width'] : null;
            $height = isset($item['height']) && (int) $item['height'] ? (int) $item['height'] : null;

            Image::resize($company->logoPath() . '/or_' . $name, $width, $height)
                ->save($company->logoPath() . '/' . $type . '_' . $name);
            $optimizerChain->optimize($company->logoPath() . '/' . $type . '_' . $name);
        }

        // Удаляем старый файл
        FileHelper::unlink($company->logoPath() . '/or_' . $name);
        if ($company->logo && is_file($company->logoPath() . '/big_' . $company->logo) && $company->logo != $name) {
            foreach ($this->sizes as $type => $item) {
                @FileHelper::unlink($company->logoPath() . '/' . $type . '_' . $company->logo);
            }
        }

        $company->logo = $name;
    }
}