<?php

namespace core\useCases\manage\CNews;


use core\components\SettingsManager;
use core\entities\CNews\CNewsPhoto;
use core\repositories\CNews\CNewsPhotoRepository;
use core\services\TransactionManager;
use core\useCases\BasePhotoService;
use Yii;

class CNewsPhotoService extends BasePhotoService
{
    public $component = 'cnews';
    public $photoEntityClass = CNewsPhoto::class;

    public function __construct(CNewsPhotoRepository $repository, TransactionManager $transaction)
    {
        $this->repository = $repository;
        $this->sizes = [
            'small' => ['width' => Yii::$app->settings->get(SettingsManager::CNEWS_SMALL_SIZE)],
            'big' => ['width' => Yii::$app->settings->get(SettingsManager::CNEWS_BIG_SIZE)],
            'max' => ['width' => Yii::$app->settings->get(SettingsManager::CNEWS_MAX_SIZE)],
        ];

        parent::__construct($transaction);
    }
}