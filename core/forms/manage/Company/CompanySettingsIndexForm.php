<?php

namespace core\forms\manage\Company;


use core\components\SettingsManager;
use core\forms\manage\CommonSettingsForm;

class CompanySettingsIndexForm extends CommonSettingsForm
{
    public $companyMetaTitle;
    public $companyMetaDescription;
    public $companyMetaKeywords;
    public $companyTitle;
    public $companyDescriptionTop;
    public $companyDescriptionTopOn;
    public $companyDescriptionBottom;
    public $companyDescriptionBottomOn;

    public function rules()
    {
        return [
            [[SettingsManager::COMPANY_DESCRIPTION_TOP_ON, SettingsManager::COMPANY_DESCRIPTION_BOTTOM_ON], 'boolean'],
            [[
                SettingsManager::COMPANY_META_TITLE,
                SettingsManager::COMPANY_META_DESCRIPTION,
                SettingsManager::COMPANY_META_KEYWORDS,
                SettingsManager::COMPANY_TITLE,
                SettingsManager::COMPANY_DESCRIPTION_TOP,
                SettingsManager::COMPANY_DESCRIPTION_BOTTOM,
            ], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            SettingsManager::COMPANY_META_TITLE => 'Meta Title',
            SettingsManager::COMPANY_META_DESCRIPTION => 'Meta Description',
            SettingsManager::COMPANY_META_KEYWORDS => 'Meta Keywords',
            SettingsManager::COMPANY_TITLE => 'Заголовок',
            SettingsManager::COMPANY_DESCRIPTION_TOP => 'Описание сверху',
            SettingsManager::COMPANY_DESCRIPTION_TOP_ON => 'Только на гл. стр.',
            SettingsManager::COMPANY_DESCRIPTION_BOTTOM => 'Описание снизу',
            SettingsManager::COMPANY_DESCRIPTION_BOTTOM_ON => 'Только на гл. стр.',
        ];
    }

}