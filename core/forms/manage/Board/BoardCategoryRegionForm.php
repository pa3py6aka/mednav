<?php

namespace core\forms\manage\Board;


use core\entities\Board\BoardCategoryRegion;
use core\forms\manage\CategoryRegionForm;

class BoardCategoryRegionForm extends CategoryRegionForm
{
    public function __construct(BoardCategoryRegion $region = null, $config = [])
    {
        parent::__construct($region, $config);
    }
}