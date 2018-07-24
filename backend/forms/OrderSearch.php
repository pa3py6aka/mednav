<?php

namespace backend\forms;

use core\entities\Company\Company;
use core\entities\User\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\entities\Order\Order;

/**
 * OrderSearch represents the model behind the search form of `core\entities\Order\Order`.
 */
class OrderSearch extends Order
{
    public $buyer;
    public $seller;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'for_company_id', 'user_id', 'delivery_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['comment', 'user_name', 'user_phone', 'user_email', 'address'], 'safe'],
            [['buyer', 'seller'], 'string'],
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
    public function search($params)
    {
        $query = Order::find()
            ->alias('o')
            ->with('forCompany', 'user.company');

        // add conditions that should always apply here


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($this->buyer) {
            $query->leftJoin(Company::tableName(). ' c', 'c.user_id=o.user_id');
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'o.id' => $this->id,
            'o.for_company_id' => $this->seller,
            'o.delivery_id' => $this->delivery_id,
            'o.status' => $this->status,
            'o.created_at' => $this->created_at,
            'o.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'o.comment', $this->comment])
            ->andFilterWhere(['like', 'o.user_name', $this->user_name])
            ->andFilterWhere(['like', 'o.user_phone', $this->user_phone])
            ->andFilterWhere(['like', 'o.user_email', $this->user_email])
            ->andFilterWhere(['like', 'o.address', $this->address]);
        $query->andFilterWhere(['or', ['o.user_id' => $this->buyer], ['c.id' => $this->buyer]]);

        return $dataProvider;
    }
}
