<?php

namespace core\useCases\manage\Brand;


use core\components\SettingsManager;
use core\entities\Brand\BrandPhoto;
use core\repositories\Brand\BrandPhotoRepository;
use core\services\TransactionManager;
use core\useCases\BasePhotoService;
use Yii;

class BrandPhotoService extends BasePhotoService
{
    public $component = 'brand';
    public $photoEntityClass = BrandPhoto::class;

    public function __construct(BrandPhotoRepository $repository, TransactionManager $transaction)
    {
        $this->repository = $repository;
        $this->sizes = [
            'small' => ['width' => Yii::$app->settings->get(SettingsManager::BRANDS_SMALL_SIZE)],
            'big' => ['width' => Yii::$app->settings->get(SettingsManager::BRANDS_BIG_SIZE)],
            'max' => ['width' => Yii::$app->settings->get(SettingsManager::BRANDS_MAX_SIZE)],
        ];

        parent::__construct($transaction);
    }
}