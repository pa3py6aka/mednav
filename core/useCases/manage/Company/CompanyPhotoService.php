<?php

namespace core\useCases\manage\Company;


use core\components\SettingsManager;
use core\entities\Company\CompanyPhoto;
use core\repositories\Company\CompanyPhotoRepository;
use core\services\TransactionManager;
use core\useCases\BasePhotoService;
use Yii;

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
}