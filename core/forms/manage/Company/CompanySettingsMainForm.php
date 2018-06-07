<?php

namespace core\forms\manage\Company;


use core\components\SettingsManager;
use core\forms\manage\CommonSettingsForm;

class CompanySettingsMainForm extends CommonSettingsForm
{
    public $companyName;
    public $companyNameUP;
    public $companyPageSize;
    public $companySmallSize;
    public $companyBigSize;
    public $companyMaxSize;
    public $companyModeration;

    public function rules()
    {
        return [
            [[SettingsManager::COMPANY_NAME, SettingsManager::COMPANY_NAME_UP], 'string'],
            [[
                SettingsManager::COMPANY_PAGE_SIZE,
                SettingsManager::COMPANY_SMALL_SIZE,
                SettingsManager::COMPANY_BIG_SIZE,
                SettingsManager::COMPANY_MAX_SIZE,
            ], 'integer'],
            [SettingsManager::COMPANY_MODERATION, 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            SettingsManager::COMPANY_NAME => 'Название',
            SettingsManager::COMPANY_NAME_UP => 'Название ППУ',
            SettingsManager::COMPANY_PAGE_SIZE => 'Позиций в листинге',
            SettingsManager::COMPANY_SMALL_SIZE => 'Размер малой копии',
            SettingsManager::COMPANY_BIG_SIZE => 'Размер большой копии',
            SettingsManager::COMPANY_MAX_SIZE => 'Максимальный размер',
            SettingsManager::COMPANY_MODERATION => 'Модерация обьявлений',
        ];
    }

}