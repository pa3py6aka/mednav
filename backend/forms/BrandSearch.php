<?php

namespace backend\forms;

use core\entities\Brand\Brand;
use yii\base\Model;
use yii\data\ActiveDataProvider;


class BrandSearch extends Brand
{
    use ArticleSearchTrait;

    private $entityClass = Brand::class;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
}
