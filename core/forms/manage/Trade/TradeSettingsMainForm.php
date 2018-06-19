<?php

namespace core\forms\manage\Trade;


use core\components\SettingsManager;
use core\forms\manage\CommonSettingsForm;

class TradeSettingsMainForm extends CommonSettingsForm
{
    public $tradeName;
    public $tradeNameUP;
    public $tradePageSize;
    public $tradeSmallSize;
    public $tradeBigSize;
    public $tradeMaxSize;
    public $tradeModeration;

    public function rules()
    {
        return [
            [[SettingsManager::TRADE_NAME, SettingsManager::TRADE_NAME_UP], 'string'],
            [[
                SettingsManager::TRADE_PAGE_SIZE,
                SettingsManager::TRADE_SMALL_SIZE,
                SettingsManager::TRADE_BIG_SIZE,
                SettingsManager::TRADE_MAX_SIZE,
            ], 'integer'],
            [SettingsManager::TRADE_MODERATION, 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            SettingsManager::TRADE_NAME => 'Название',
            SettingsManager::TRADE_NAME_UP => 'Название ППУ',
            SettingsManager::TRADE_PAGE_SIZE => 'Позиций в листинге',
            SettingsManager::TRADE_SMALL_SIZE => 'Размер малой копии',
            SettingsManager::TRADE_BIG_SIZE => 'Размер большой копии',
            SettingsManager::TRADE_MAX_SIZE => 'Максимальный размер',
            SettingsManager::TRADE_MODERATION => 'Модерация товаров',
        ];
    }

}