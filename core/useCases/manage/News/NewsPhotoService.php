<?php

namespace core\useCases\manage\News;


use core\components\SettingsManager;
use core\entities\News\NewsPhoto;
use core\repositories\News\NewsPhotoRepository;
use core\services\TransactionManager;
use core\useCases\BasePhotoService;
use Yii;

class NewsPhotoService extends BasePhotoService
{
    public $component = 'news';
    public $photoEntityClass = NewsPhoto::class;

    public function __construct(NewsPhotoRepository $repository, TransactionManager $transaction)
    {
        $this->repository = $repository;
        $this->sizes = [
            'small' => ['width' => Yii::$app->settings->get(SettingsManager::NEWS_SMALL_SIZE)],
            'big' => ['width' => Yii::$app->settings->get(SettingsManager::NEWS_BIG_SIZE)],
            'max' => ['width' => Yii::$app->settings->get(SettingsManager::NEWS_MAX_SIZE)],
        ];

        parent::__construct($transaction);
    }
}