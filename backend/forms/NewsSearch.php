<?php

namespace backend\forms;

use core\entities\News\News;
use yii\base\Model;


class NewsSearch extends News
{
    use ArticleSearchTrait;

    private $entityClass = News::class;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
}
