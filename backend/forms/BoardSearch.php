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
    public $category;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'author_id', 'price', 'currency', 'term_id', 'geo_id', 'status', 'active_until', 'created_at', 'updated_at'], 'integer'],
            [['name', 'slug', 'title', 'description', 'keywords', 'note', 'price_from', 'full_text'], 'safe'],
            [['author', 'typeParameter', 'userType', 'category'], 'safe'],
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

        if ($this->category) {
            $query->joinWith('category cat');
        }

        /*if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }*/

        // grid filtering conditions
        $query->andFilterWhere([
            'b.id' => $this->id,
            'b.author_id' => $this->author_id,
            //'b.category_id' => $this->category_id,
            'b.price' => $this->price,
            'b.currency' => $this->currency,
            'b.term_id' => $this->term_id,
            'b.geo_id' => $this->geo_id,
            'b.status' => $this->status,
            'b.active_until' => $this->active_until,
            'b.created_at' => $this->created_at,
            'b.updated_at' => $this->updated_at,
            'param.option_id' => $this->typeParameter,
            'u.type' => $this->userType,
        ]);

        $query->andFilterWhere(['like', 'b.name', $this->name])
            ->andFilterWhere(['like', 'b.slug', $this->slug])
            ->andFilterWhere(['like', 'b.title', $this->title])
            ->andFilterWhere(['like', 'b.description', $this->description])
            ->andFilterWhere(['like', 'b.keywords', $this->keywords])
            ->andFilterWhere(['like', 'b.note', $this->note])
            ->andFilterWhere(['like', 'b.price_from', $this->price_from])
            ->andFilterWhere(['like', 'b.full_text', $this->full_text])
            ->andFilterWhere([
                'or',
                [
                    'or',
                    ['like', 'u.last_name', $this->author],
                    ['like', 'u.name', $this->author],
                    ['like', 'u.patronymic', $this->author]
                ],
                ['b.author_id' => $this->author]
            ])
            ->andFilterWhere([
                'or',
                ['like', 'cat.name', $this->category],
                ['b.category_id' => $this->category]
            ]);;

        return $dataProvider;
    }
}
