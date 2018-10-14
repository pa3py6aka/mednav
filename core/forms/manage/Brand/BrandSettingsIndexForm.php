<?php

namespace core\forms\manage\Brand;


use core\components\SettingsManager;
use core\forms\manage\CommonSettingsForm;

class BrandSettingsIndexForm extends CommonSettingsForm
{
    public $brandsMetaTitle;
    public $brandsMetaDescription;
    public $brandsMetaKeywords;
    public $brandsTitle;
    public $brandsDescriptionTop;
    public $brandsDescriptionTopOn;
    public $brandsDescriptionBottom;
    public $brandsDescriptionBottomOn;

    public function rules()
    {
        return [
            [[SettingsManager::BRANDS_DESCRIPTION_TOP_ON, SettingsManager::BRANDS_DESCRIPTION_BOTTOM_ON], 'boolean'],
            [[
                SettingsManager::BRANDS_META_TITLE,
                SettingsManager::BRANDS_META_DESCRIPTION,
                SettingsManager::BRANDS_META_KEYWORDS,
                SettingsManager::BRANDS_TITLE,
                SettingsManager::BRANDS_DESCRIPTION_TOP,
                SettingsManager::BRANDS_DESCRIPTION_BOTTOM,
            ], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            SettingsManager::BRANDS_META_TITLE => 'Meta Title',
            SettingsManager::BRANDS_META_DESCRIPTION => 'Meta Description',
            SettingsManager::BRANDS_META_KEYWORDS => 'Meta Keywords',
            SettingsManager::BRANDS_TITLE => 'Заголовок',
            SettingsManager::BRANDS_DESCRIPTION_TOP => 'Описание сверху',
            SettingsManager::BRANDS_DESCRIPTION_TOP_ON => 'Только на гл. стр.',
            SettingsManager::BRANDS_DESCRIPTION_BOTTOM => 'Описание снизу',
            SettingsManager::BRANDS_DESCRIPTION_BOTTOM_ON => 'Только на гл. стр.',
        ];
    }

}