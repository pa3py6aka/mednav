<?php

namespace core\forms\manage\Trade;


use core\components\SettingsManager;
use core\forms\manage\CommonSettingsForm;

class TradeSettingsIndexForm extends CommonSettingsForm
{
    public $tradeMetaTitle;
    public $tradeMetaDescription;
    public $tradeMetaKeywords;
    public $tradeTitle;
    public $tradeDescriptionTop;
    public $tradeDescriptionTopOn;
    public $tradeDescriptionBottom;
    public $tradeDescriptionBottomOn;

    public function rules()
    {
        return [
            [[SettingsManager::TRADE_DESCRIPTION_TOP_ON, SettingsManager::TRADE_DESCRIPTION_BOTTOM_ON], 'boolean'],
            [[
                SettingsManager::TRADE_META_TITLE,
                SettingsManager::TRADE_META_DESCRIPTION,
                SettingsManager::TRADE_META_KEYWORDS,
                SettingsManager::TRADE_TITLE,
                SettingsManager::TRADE_DESCRIPTION_TOP,
                SettingsManager::TRADE_DESCRIPTION_BOTTOM,
            ], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            SettingsManager::TRADE_META_TITLE => 'Meta Title',
            SettingsManager::TRADE_META_DESCRIPTION => 'Meta Description',
            SettingsManager::TRADE_META_KEYWORDS => 'Meta Keywords',
            SettingsManager::TRADE_TITLE => 'Заголовок',
            SettingsManager::TRADE_DESCRIPTION_TOP => 'Описание сверху',
            SettingsManager::TRADE_DESCRIPTION_TOP_ON => 'Только на гл. стр.',
            SettingsManager::TRADE_DESCRIPTION_BOTTOM => 'Описание снизу',
            SettingsManager::TRADE_DESCRIPTION_BOTTOM_ON => 'Только на гл. стр.',
        ];
    }

}