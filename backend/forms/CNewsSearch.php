<?php

namespace backend\forms;

use core\entities\CNews\CNews;
use yii\base\Model;


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
