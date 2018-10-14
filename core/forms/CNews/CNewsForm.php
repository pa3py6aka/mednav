<?php

namespace core\forms\CNews;


use core\entities\CNews\CNews;
use core\forms\ArticleCommonForm;

class CNewsForm extends ArticleCommonForm
{
    public function __construct(CNews $cnews = null, array $config = [])
    {
        parent::__construct($cnews, $config);
    }
}