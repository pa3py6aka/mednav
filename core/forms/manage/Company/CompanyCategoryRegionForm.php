<?php

namespace core\forms\manage\Company;


use core\entities\Company\CompanyCategoryRegion;
use core\forms\manage\CategoryRegionForm;

class CompanyCategoryRegionForm extends CategoryRegionForm
{
    public function __construct(CompanyCategoryRegion $region = null, $config = [])
    {
        parent::__construct($region, $config);
    }
}