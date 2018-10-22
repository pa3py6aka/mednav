<?php

namespace core\forms\manage\Expo;


use core\components\SettingsManager;
use core\forms\manage\CommonSettingsForm;

class ExpoSettingsIndexForm extends CommonSettingsForm
{
    public $expoMetaTitle;
    public $expoMetaDescription;
    public $expoMetaKeywords;
    public $expoTitle;
    public $expoDescriptionTop;
    public $expoDescriptionTopOn;
    public $expoDescriptionBottom;
    public $expoDescriptionBottomOn;

    public function rules()
    {
        return [
            [[SettingsManager::EXPO_DESCRIPTION_TOP_ON, SettingsManager::EXPO_DESCRIPTION_BOTTOM_ON], 'boolean'],
            [[
                SettingsManager::EXPO_META_TITLE,
                SettingsManager::EXPO_META_DESCRIPTION,
                SettingsManager::EXPO_META_KEYWORDS,
                SettingsManager::EXPO_TITLE,
                SettingsManager::EXPO_DESCRIPTION_TOP,
                SettingsManager::EXPO_DESCRIPTION_BOTTOM,
            ], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            SettingsManager::EXPO_META_TITLE => 'Meta Title',
            SettingsManager::EXPO_META_DESCRIPTION => 'Meta Description',
            SettingsManager::EXPO_META_KEYWORDS => 'Meta Keywords',
            SettingsManager::EXPO_TITLE => 'Заголовок',
            SettingsManager::EXPO_DESCRIPTION_TOP => 'Описание сверху',
            SettingsManager::EXPO_DESCRIPTION_TOP_ON => 'Только на гл. стр.',
            SettingsManager::EXPO_DESCRIPTION_BOTTOM => 'Описание снизу',
            SettingsManager::EXPO_DESCRIPTION_BOTTOM_ON => 'Только на гл. стр.',
        ];
    }

}