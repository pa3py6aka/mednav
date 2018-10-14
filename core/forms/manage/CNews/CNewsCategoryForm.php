<?php

namespace core\forms\manage\CNews;


use core\entities\CNews\CNewsCategory;
use core\forms\manage\CategoryForm;

class CNewsCategoryForm extends CategoryForm
{
    public function __construct(CNewsCategory $category = null, $config = [])
    {
        parent::__construct(CNewsCategory::class, $category, $config);
    }
}