<?php

namespace core\forms\manage\Trade;


use core\components\Settings;
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
    public $tradeShowEmptyCategories;

    public function rules()
    {
        return [
            [[Settings::TRADE_NAME, Settings::TRADE_NAME_UP], 'string'],
            [[
                Settings::TRADE_PAGE_SIZE,
                Settings::TRADE_SMALL_SIZE,
                Settings::TRADE_BIG_SIZE,
                Settings::TRADE_MAX_SIZE,
            ], 'integer'],
            [[Settings::TRADE_MODERATION, Settings::TRADE_SHOW_EMPTY_CATEGORIES], 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            Settings::TRADE_NAME => 'Название',
            Settings::TRADE_NAME_UP => 'Название ППУ',
            Settings::TRADE_PAGE_SIZE => 'Позиций в листинге',
            Settings::TRADE_SMALL_SIZE => 'Размер малой копии',
            Settings::TRADE_BIG_SIZE => 'Размер большой копии',
            Settings::TRADE_MAX_SIZE => 'Максимальный размер',
            Settings::TRADE_MODERATION => 'Модерация товаров',
            Settings::TRADE_SHOW_EMPTY_CATEGORIES => 'Скрывать разделы без товаров',
        ];
    }

}