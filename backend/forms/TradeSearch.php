<?php

namespace backend\forms;

use core\entities\Trade\Trade;
use core\entities\Trade\TradeCategory;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BoardSearch represents the model behind the search form of `core\entities\Trade\Trade`.
 */
class TradeSearch extends Trade
{
    public $user;
    public $userType;
    public $company;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'category_id', 'price', 'geo_id', 'status'], 'integer'],
            [['name', 'slug', 'title', 'description', 'keywords', 'note'], 'safe'],
            [['user', 'userType', 'company_id'], 'safe'],
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
        $query = Trade::find()
            ->alias('t')
            ->with('category')
            ->joinWith('company c');

        if ($tab == 'active') {
            $query->active('t');
        } else if ($tab == 'moderation') {
            $query->onModeration('t');
        } else if ($tab == 'deleted') {
            $query->deleted('t');
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['t.id' => SORT_DESC]],
            'pagination' => [
                'pageSizeLimit' => [15, 500],
                'defaultPageSize' => 15,
            ],
        ]);

        $dataProvider->sort->attributes['t.id'] = [
            'asc' => ['t.id' => SORT_ASC],
            'desc' => ['t.id' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['user'] = [
            'asc' => ['t.user_id' => SORT_ASC],
            'desc' => ['t.user_id' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($this->category_id) {
            $category = TradeCategory::findOne($this->category_id);
            $categoryIds = $category->getDescendants()->select('id')->column();
            $categoryIds[] = $this->category_id;
            $query->andFilterWhere(['t.category_id' => $categoryIds]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            't.id' => $this->id,
            't.user_id' => $this->user_id,
            't.price' => $this->price,
            't.geo_id' => $this->geo_id,
            't.status' => $this->status,
            't.created_at' => $this->created_at,
            't.updated_at' => $this->updated_at,
            'u.type' => $this->userType,
        ]);

        $query->andFilterWhere(['like', 't.name', $this->name])
            ->andFilterWhere(['like', 't.slug', $this->slug])
            ->andFilterWhere(['like', 't.description', $this->description])
            ->andFilterWhere(['like', 't.note', $this->note])
            ->andFilterWhere([
                'or',
                ['like', 'c.name', $this->company_id],
                ['t.company_id' => $this->company_id]
            ]);

        return $dataProvider;
    }
}
