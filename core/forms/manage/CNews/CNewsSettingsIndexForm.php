<?php

namespace core\forms\manage\CNews;


use core\components\SettingsManager;
use core\forms\manage\CommonSettingsForm;

class CNewsSettingsIndexForm extends CommonSettingsForm
{
    public $cnewsMetaTitle;
    public $cnewsMetaDescription;
    public $cnewsMetaKeywords;
    public $cnewsTitle;
    public $cnewsDescriptionTop;
    public $cnewsDescriptionTopOn;
    public $cnewsDescriptionBottom;
    public $cnewsDescriptionBottomOn;

    public function rules()
    {
        return [
            [[SettingsManager::CNEWS_DESCRIPTION_TOP_ON, SettingsManager::CNEWS_DESCRIPTION_BOTTOM_ON], 'boolean'],
            [[
                SettingsManager::CNEWS_META_TITLE,
                SettingsManager::CNEWS_META_DESCRIPTION,
                SettingsManager::CNEWS_META_KEYWORDS,
                SettingsManager::CNEWS_TITLE,
                SettingsManager::CNEWS_DESCRIPTION_TOP,
                SettingsManager::CNEWS_DESCRIPTION_BOTTOM,
            ], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            SettingsManager::CNEWS_META_TITLE => 'Meta Title',
            SettingsManager::CNEWS_META_DESCRIPTION => 'Meta Description',
            SettingsManager::CNEWS_META_KEYWORDS => 'Meta Keywords',
            SettingsManager::CNEWS_TITLE => 'Заголовок',
            SettingsManager::CNEWS_DESCRIPTION_TOP => 'Описание сверху',
            SettingsManager::CNEWS_DESCRIPTION_TOP_ON => 'Только на гл. стр.',
            SettingsManager::CNEWS_DESCRIPTION_BOTTOM => 'Описание снизу',
            SettingsManager::CNEWS_DESCRIPTION_BOTTOM_ON => 'Только на гл. стр.',
        ];
    }

}