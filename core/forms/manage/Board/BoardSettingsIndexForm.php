<?php

namespace core\forms\manage\Board;


use core\components\SettingsManager;
use core\forms\manage\CommonSettingsForm;

class BoardSettingsIndexForm extends CommonSettingsForm
{
    public $boardMetaTitle;
    public $boardMetaDescription;
    public $boardMetaKeywords;
    public $boardTitle;
    public $boardDescriptionTop;
    public $boardDescriptionTopOn;
    public $boardDescriptionBottom;
    public $boardDescriptionBottomOn;

    public function rules()
    {
        return [
            [[SettingsManager::BOARD_DESCRIPTION_TOP_ON, SettingsManager::BOARD_DESCRIPTION_BOTTOM_ON], 'boolean'],
            [[
                SettingsManager::BOARD_META_TITLE,
                SettingsManager::BOARD_META_DESCRIPTION,
                SettingsManager::BOARD_META_KEYWORDS,
                SettingsManager::BOARD_TITLE,
                SettingsManager::BOARD_DESCRIPTION_TOP,
                SettingsManager::BOARD_DESCRIPTION_BOTTOM,
            ], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            SettingsManager::BOARD_META_TITLE => 'Meta Title',
            SettingsManager::BOARD_META_DESCRIPTION => 'Meta Description',
            SettingsManager::BOARD_META_KEYWORDS => 'Meta Keywords',
            SettingsManager::BOARD_TITLE => 'Заголовок',
            SettingsManager::BOARD_DESCRIPTION_TOP => 'Описание сверху',
            SettingsManager::BOARD_DESCRIPTION_TOP_ON => 'Только на гл. стр.',
            SettingsManager::BOARD_DESCRIPTION_BOTTOM => 'Описание снизу',
            SettingsManager::BOARD_DESCRIPTION_BOTTOM_ON => 'Только на гл. стр.',
        ];
    }

}