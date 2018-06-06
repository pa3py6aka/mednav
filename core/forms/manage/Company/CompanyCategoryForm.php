<?php

namespace core\forms\manage\Company;


use core\entities\Company\CompanyCategory;
use core\forms\manage\CategoryForm;

class CompanyCategoryForm extends CategoryForm
{
    public function __construct(CompanyCategory $category = null, $config = [])
    {
        parent::__construct(CompanyCategory::class, $category, $config);
    }
}