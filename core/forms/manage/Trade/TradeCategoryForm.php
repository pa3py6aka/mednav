<?php

namespace core\forms\manage\Trade;


use core\entities\Trade\TradeCategory;
use core\forms\manage\CategoryForm;

class TradeCategoryForm extends CategoryForm
{
    public function __construct(TradeCategory $category = null, $config = [])
    {
        parent::__construct(TradeCategory::class, $category, $config);
    }
}