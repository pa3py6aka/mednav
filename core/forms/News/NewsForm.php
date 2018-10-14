<?php

namespace core\forms\News;


use core\entities\News\News;
use core\forms\ArticleCommonForm;

class NewsForm extends ArticleCommonForm
{
    public function __construct(News $news = null, array $config = [])
    {
        parent::__construct($news, $config);
    }
}