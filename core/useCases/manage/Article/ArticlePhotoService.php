<?php

namespace core\useCases\manage\Article;


use core\components\SettingsManager;
use core\entities\Article\ArticlePhoto;
use core\repositories\Article\ArticlePhotoRepository;
use core\services\TransactionManager;
use core\useCases\BasePhotoService;
use Yii;

class ArticlePhotoService extends BasePhotoService
{
    public $component = 'articles';
    public $photoEntityClass = ArticlePhoto::class;

    public function __construct(ArticlePhotoRepository $repository, TransactionManager $transaction)
    {
        $this->repository = $repository;
        $this->sizes = [
            'small' => ['width' => Yii::$app->settings->get(SettingsManager::ARTICLE_SMALL_SIZE)],
            'big' => ['width' => Yii::$app->settings->get(SettingsManager::ARTICLE_BIG_SIZE)],
            'max' => ['width' => Yii::$app->settings->get(SettingsManager::ARTICLE_MAX_SIZE)],
        ];

        parent::__construct($transaction);
    }
}