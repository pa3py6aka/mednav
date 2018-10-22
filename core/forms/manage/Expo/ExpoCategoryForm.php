<?php

namespace core\forms\manage\Expo;


use core\entities\Expo\ExpoCategory;
use core\forms\manage\CategoryForm;

class ExpoCategoryForm extends CategoryForm
{
    public function __construct(ExpoCategory $category = null, $config = [])
    {
        parent::__construct(ExpoCategory::class, $category, $config);
    }
}