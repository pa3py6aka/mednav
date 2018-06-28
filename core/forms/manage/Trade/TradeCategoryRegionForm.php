<?php

namespace core\forms\manage\Trade;


use core\entities\Trade\TradeCategoryRegion;
use core\forms\manage\CategoryRegionForm;

class TradeCategoryRegionForm extends CategoryRegionForm
{
    public function __construct(TradeCategoryRegion $region = null, $config = [])
    {
        parent::__construct($region, $config);
    }
}