<?php

namespace core\forms\manage\Brand;


use core\components\SettingsManager;
use core\forms\manage\CommonSettingsForm;

class BrandSettingsMainForm extends CommonSettingsForm
{
    public $brandsName;
    public $brandsNameUP;
    public $brandsPageSize;
    public $brandsSmallSize;
    public $brandsBigSize;
    public $brandsMaxSize;
    public $brandsModeration;

    public function rules()
    {
        return [
            [[SettingsManager::BRANDS_NAME, SettingsManager::BRANDS_NAME_UP], 'string'],
            [[
                SettingsManager::BRANDS_PAGE_SIZE,
                SettingsManager::BRANDS_SMALL_SIZE,
                SettingsManager::BRANDS_BIG_SIZE,
                SettingsManager::BRANDS_MAX_SIZE,
            ], 'integer'],
            [SettingsManager::BRANDS_MODERATION, 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            SettingsManager::BRANDS_NAME => 'Название',
            SettingsManager::BRANDS_NAME_UP => 'Название ППУ',
            SettingsManager::BRANDS_PAGE_SIZE => 'Позиций в листинге',
            SettingsManager::BRANDS_SMALL_SIZE => 'Размер малой копии',
            SettingsManager::BRANDS_BIG_SIZE => 'Размер большой копии',
            SettingsManager::BRANDS_MAX_SIZE => 'Максимальный размер',
            SettingsManager::BRANDS_MODERATION => 'Модерация обьявлений',
        ];
    }

}