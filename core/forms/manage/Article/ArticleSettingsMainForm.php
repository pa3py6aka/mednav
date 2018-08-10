<?php

namespace core\forms\manage\Article;


use core\components\SettingsManager;
use core\forms\manage\CommonSettingsForm;

class ArticleSettingsMainForm extends CommonSettingsForm
{
    public $articleName;
    public $articleNameUP;
    public $articlePageSize;
    public $articleSmallSize;
    public $articleBigSize;
    public $articleMaxSize;
    public $articleModeration;

    public function rules()
    {
        return [
            [[SettingsManager::ARTICLE_NAME, SettingsManager::ARTICLE_NAME_UP], 'string'],
            [[
                SettingsManager::ARTICLE_PAGE_SIZE,
                SettingsManager::ARTICLE_SMALL_SIZE,
                SettingsManager::ARTICLE_BIG_SIZE,
                SettingsManager::ARTICLE_MAX_SIZE,
            ], 'integer'],
            [SettingsManager::ARTICLE_MODERATION, 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            SettingsManager::ARTICLE_NAME => 'Название',
            SettingsManager::ARTICLE_NAME_UP => 'Название ППУ',
            SettingsManager::ARTICLE_PAGE_SIZE => 'Позиций в листинге',
            SettingsManager::ARTICLE_SMALL_SIZE => 'Размер малой копии',
            SettingsManager::ARTICLE_BIG_SIZE => 'Размер большой копии',
            SettingsManager::ARTICLE_MAX_SIZE => 'Максимальный размер',
            SettingsManager::ARTICLE_MODERATION => 'Модерация обьявлений',
        ];
    }

}