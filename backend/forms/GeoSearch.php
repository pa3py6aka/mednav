<?php

namespace backend\forms;


use core\entities\Geo;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class GeoSearch extends Model
{
    public $id;
    public $name;
    public $slug;
    public $popular;
    public $active;

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'slug', 'popular', 'active'], 'safe'],
        ];
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = Geo::find()->andWhere(['>', 'depth', 0]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['lft' => SORT_ASC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'popular' => $this->popular,
            'active' => $this->active
        ]);
        $query
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'slug', $this->slug]);

        return $dataProvider;
    }
}