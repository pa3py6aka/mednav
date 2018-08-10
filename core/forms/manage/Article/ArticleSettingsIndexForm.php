<?php

namespace core\forms\manage\Article;


use core\components\SettingsManager;
use core\forms\manage\CommonSettingsForm;

class ArticleSettingsIndexForm extends CommonSettingsForm
{
    public $articleMetaTitle;
    public $articleMetaDescription;
    public $articleMetaKeywords;
    public $articleTitle;
    public $articleDescriptionTop;
    public $articleDescriptionTopOn;
    public $articleDescriptionBottom;
    public $articleDescriptionBottomOn;

    public function rules()
    {
        return [
            [[SettingsManager::ARTICLE_DESCRIPTION_TOP_ON, SettingsManager::ARTICLE_DESCRIPTION_BOTTOM_ON], 'boolean'],
            [[
                SettingsManager::ARTICLE_META_TITLE,
                SettingsManager::ARTICLE_META_DESCRIPTION,
                SettingsManager::ARTICLE_META_KEYWORDS,
                SettingsManager::ARTICLE_TITLE,
                SettingsManager::ARTICLE_DESCRIPTION_TOP,
                SettingsManager::ARTICLE_DESCRIPTION_BOTTOM,
            ], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            SettingsManager::ARTICLE_META_TITLE => 'Meta Title',
            SettingsManager::ARTICLE_META_DESCRIPTION => 'Meta Description',
            SettingsManager::ARTICLE_META_KEYWORDS => 'Meta Keywords',
            SettingsManager::ARTICLE_TITLE => 'Заголовок',
            SettingsManager::ARTICLE_DESCRIPTION_TOP => 'Описание сверху',
            SettingsManager::ARTICLE_DESCRIPTION_TOP_ON => 'Только на гл. стр.',
            SettingsManager::ARTICLE_DESCRIPTION_BOTTOM => 'Описание снизу',
            SettingsManager::ARTICLE_DESCRIPTION_BOTTOM_ON => 'Только на гл. стр.',
        ];
    }

}