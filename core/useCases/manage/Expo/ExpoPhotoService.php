<?php

namespace core\useCases\manage\Expo;


use core\components\SettingsManager;
use core\entities\Expo\ExpoPhoto;
use core\repositories\Expo\ExpoPhotoRepository;
use core\services\TransactionManager;
use core\useCases\BasePhotoService;
use Yii;

class ExpoPhotoService extends BasePhotoService
{
    public $component = 'brand';
    public $photoEntityClass = ExpoPhoto::class;

    public function __construct(ExpoPhotoRepository $repository, TransactionManager $transaction)
    {
        $this->repository = $repository;
        $this->sizes = [
            'small' => ['width' => Yii::$app->settings->get(SettingsManager::EXPO_SMALL_SIZE)],
            'big' => ['width' => Yii::$app->settings->get(SettingsManager::EXPO_BIG_SIZE)],
            'max' => ['width' => Yii::$app->settings->get(SettingsManager::EXPO_MAX_SIZE)],
        ];

        parent::__construct($transaction);
    }
}