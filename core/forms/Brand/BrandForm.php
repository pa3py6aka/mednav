<?php

namespace core\forms\Brand;


use core\entities\Brand\Brand;
use core\forms\ArticleCommonForm;

class BrandForm extends ArticleCommonForm
{
    public function __construct(Brand $brand = null, array $config = [])
    {
        parent::__construct($brand, $config);
    }
}