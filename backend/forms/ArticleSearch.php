<?php

namespace backend\forms;


use yii\base\Model;
use core\entities\Article\Article;

/**
 * ArticleSearch represents the model behind the search form of `core\entities\Article\Article`.
 */
class ArticleSearch extends Article
{
    use ArticleSearchTrait;

    private $entityClass = Article::class;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
}
