<?php

namespace core\forms\manage\News;


use core\entities\News\NewsCategory;
use core\forms\manage\CategoryForm;

class NewsCategoryForm extends CategoryForm
{
    public function __construct(NewsCategory $category = null, $config = [])
    {
        parent::__construct(NewsCategory::class, $category, $config);
    }
}