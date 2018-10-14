<?php

namespace core\forms\manage\CNews;


use core\components\SettingsManager;
use core\forms\manage\CommonSettingsForm;

class CNewsSettingsMainForm extends CommonSettingsForm
{
    public $cnewsName;
    public $cnewsNameUP;
    public $cnewsPageSize;
    public $cnewsSmallSize;
    public $cnewsBigSize;
    public $cnewsMaxSize;
    public $cnewsModeration;

    public function rules()
    {
        return [
            [[SettingsManager::CNEWS_NAME, SettingsManager::CNEWS_NAME_UP], 'string'],
            [[
                SettingsManager::CNEWS_PAGE_SIZE,
                SettingsManager::CNEWS_SMALL_SIZE,
                SettingsManager::CNEWS_BIG_SIZE,
                SettingsManager::CNEWS_MAX_SIZE,
            ], 'integer'],
            [SettingsManager::CNEWS_MODERATION, 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            SettingsManager::CNEWS_NAME => 'Название',
            SettingsManager::CNEWS_NAME_UP => 'Название ППУ',
            SettingsManager::CNEWS_PAGE_SIZE => 'Позиций в листинге',
            SettingsManager::CNEWS_SMALL_SIZE => 'Размер малой копии',
            SettingsManager::CNEWS_BIG_SIZE => 'Размер большой копии',
            SettingsManager::CNEWS_MAX_SIZE => 'Максимальный размер',
            SettingsManager::CNEWS_MODERATION => 'Модерация обьявлений',
        ];
    }

}