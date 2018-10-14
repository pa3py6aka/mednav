<?php

namespace core\forms\manage\News;


use core\components\SettingsManager;
use core\forms\manage\CommonSettingsForm;

class NewsSettingsIndexForm extends CommonSettingsForm
{
    public $newsMetaTitle;
    public $newsMetaDescription;
    public $newsMetaKeywords;
    public $newsTitle;
    public $newsDescriptionTop;
    public $newsDescriptionTopOn;
    public $newsDescriptionBottom;
    public $newsDescriptionBottomOn;

    public function rules()
    {
        return [
            [[SettingsManager::NEWS_DESCRIPTION_TOP_ON, SettingsManager::NEWS_DESCRIPTION_BOTTOM_ON], 'boolean'],
            [[
                SettingsManager::NEWS_META_TITLE,
                SettingsManager::NEWS_META_DESCRIPTION,
                SettingsManager::NEWS_META_KEYWORDS,
                SettingsManager::NEWS_TITLE,
                SettingsManager::NEWS_DESCRIPTION_TOP,
                SettingsManager::NEWS_DESCRIPTION_BOTTOM,
            ], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            SettingsManager::NEWS_META_TITLE => 'Meta Title',
            SettingsManager::NEWS_META_DESCRIPTION => 'Meta Description',
            SettingsManager::NEWS_META_KEYWORDS => 'Meta Keywords',
            SettingsManager::NEWS_TITLE => 'Заголовок',
            SettingsManager::NEWS_DESCRIPTION_TOP => 'Описание сверху',
            SettingsManager::NEWS_DESCRIPTION_TOP_ON => 'Только на гл. стр.',
            SettingsManager::NEWS_DESCRIPTION_BOTTOM => 'Описание снизу',
            SettingsManager::NEWS_DESCRIPTION_BOTTOM_ON => 'Только на гл. стр.',
        ];
    }

}