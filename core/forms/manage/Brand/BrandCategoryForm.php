<?php

namespace core\forms\manage\Brand;


use core\entities\Brand\BrandCategory;
use core\forms\manage\CategoryForm;

class BrandCategoryForm extends CategoryForm
{
    public function __construct(BrandCategory $category = null, $config = [])
    {
        parent::__construct(BrandCategory::class, $category, $config);
    }
}