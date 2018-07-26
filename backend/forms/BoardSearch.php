<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\entities\Board\Board;

/**
 * BoardSearch represents the model behind the search form of `core\entities\Board\Board`.
 */
class BoardSearch extends Board
{
    public $author;
    public $typeParameter;
    public $userType;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'author_id', 'category_id', 'price', 'currency', 'term_id', 'geo_id', 'status', 'active_until', 'created_at', 'updated_at'], 'integer'],
            [['name', 'slug', 'title', 'description', 'keywords', 'note', 'price_from', 'full_text'], 'safe'],
            [['author', 'typeParameter', 'userType'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $tab = 'active')
    {
        $query = Board::find()
            ->alias('b')
            ->with('category', 'typeBoardParameter.option')
            ->joinWith('author u')
            ->joinWith('typeBoardParameter param');

        if ($tab == 'active') {
            $query->active('b');
        } else if ($tab == 'archive') {
            $query->archive('b');
        } else if ($tab == 'moderation') {
            $query->onModeration('b');
        } else if ($tab == 'deleted') {
            $query->deleted('b');
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['b.id' => SORT_DESC]],
            'pagination' => [
                'pageSizeLimit' => [15, 500],
                'defaultPageSize' => 15,
            ],
        ]);

        $dataProvider->sort->attributes['b.id'] = [
            'asc' => ['b.id' => SORT_ASC],
            'desc' => ['b.id' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['author'] = [
            'asc' => ['b.author_id' => SORT_ASC],
            'desc' => ['b.author_id' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['userType'] = [
            'asc' => ['u.type' => SORT_ASC],
            'desc' => ['u.type' => SORT_DESC],
        ];

        $this->load($params);

        /*if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }*/

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'author_id' => $this->author_id,
            'category_id' => $this->category_id,
            'price' => $this->price,
            'currency' => $this->currency,
            'term_id' => $this->term_id,
            'geo_id' => $this->geo_id,
            'status' => $this->status,
            'active_until' => $this->active_until,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'param.option_id' => $this->typeParameter,
            'u.type' => $this->userType,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'keywords', $this->keywords])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'price_from', $this->price_from])
            ->andFilterWhere(['like', 'full_text', $this->full_text]);

        return $dataProvider;
    }
}
