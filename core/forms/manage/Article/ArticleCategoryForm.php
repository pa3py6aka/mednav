<?php

namespace core\forms\manage\Article;


use core\entities\Article\ArticleCategory;
use core\forms\manage\CategoryForm;

class ArticleCategoryForm extends CategoryForm
{
    public function __construct(ArticleCategory $category = null, $config = [])
    {
        parent::__construct(ArticleCategory::class, $category, $config);
    }
}