<?php

namespace core\forms\manage\News;


use core\components\SettingsManager;
use core\forms\manage\CommonSettingsForm;

class NewsSettingsMainForm extends CommonSettingsForm
{
    public $newsName;
    public $newsNameUP;
    public $newsPageSize;
    public $newsSmallSize;
    public $newsBigSize;
    public $newsMaxSize;
    public $newsModeration;

    public function rules()
    {
        return [
            [[SettingsManager::NEWS_NAME, SettingsManager::NEWS_NAME_UP], 'string'],
            [[
                SettingsManager::NEWS_PAGE_SIZE,
                SettingsManager::NEWS_SMALL_SIZE,
                SettingsManager::NEWS_BIG_SIZE,
                SettingsManager::NEWS_MAX_SIZE,
            ], 'integer'],
            [SettingsManager::NEWS_MODERATION, 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            SettingsManager::NEWS_NAME => 'Название',
            SettingsManager::NEWS_NAME_UP => 'Название ППУ',
            SettingsManager::NEWS_PAGE_SIZE => 'Позиций в листинге',
            SettingsManager::NEWS_SMALL_SIZE => 'Размер малой копии',
            SettingsManager::NEWS_BIG_SIZE => 'Размер большой копии',
            SettingsManager::NEWS_MAX_SIZE => 'Максимальный размер',
            SettingsManager::NEWS_MODERATION => 'Модерация обьявлений',
        ];
    }

}