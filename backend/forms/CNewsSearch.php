<?php

namespace backend\forms;

use core\entities\CNews\CNews;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


class CNewsSearch extends CNews
{
    use ArticleSearchTrait;

    private $entityClass = CNews::class;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }
}
