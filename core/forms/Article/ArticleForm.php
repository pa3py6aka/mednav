<?php

namespace core\forms\Article;


use core\entities\Article\Article;
use core\forms\ArticleCommonForm;

class ArticleForm extends ArticleCommonForm
{
    public function __construct(Article $article = null, array $config = [])
    {
        parent::__construct($article, $config);
    }
}