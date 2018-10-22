<?php

namespace backend\forms;


use core\entities\Expo\Expo;
use yii\base\Model;


class ExpoSearch extends Expo
{
    use ArticleSearchTrait;

    private $entityClass = Expo::class;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
}
