<?php

namespace backend\forms;

use core\entities\Company\Company;
use core\entities\Order\Order;
use core\entities\User\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\entities\Order\UserOrder;

/**
 * UserOrderSearch represents the model behind the search form of `core\entities\Order\UserOrder`.
 */
class FullOrderSearch extends UserOrder
{
    public $buyer;
    public $sellers;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['user_name', 'user_phone', 'user_email', 'address'], 'safe'],
            [['buyer', 'sellers'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
        $query = UserOrder::find()
            ->alias('uo')
            ->with('orders.forCompany', 'user');

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
            $query->leftJoin(Company::tableName() . ' c', 'c.user_id=uo.user_id');
            $query->andFilterWhere([
                'or',
                ['uo.user_id' => $this->buyer],
                ['c.id' => $this->buyer],
            ]);
        }
        if ($this->sellers) {
            $query->leftJoin(Order::tableName() . ' o', 'o.user_order_id=uo.id');
            $query->andFilterWhere(['o.for_company_id' => $this->sellers]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'uo.id' => $this->id,
            'uo.user_id' => $this->user_id,
            'uo.status' => $this->status,
            'uo.created_at' => $this->created_at,
            'uo.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'uo.user_name', $this->user_name])
            ->andFilterWhere(['like', 'uo.user_phone', $this->user_phone])
            ->andFilterWhere(['like', 'uo.user_email', $this->user_email])
            ->andFilterWhere(['like', 'uo.address', $this->address]);

        return $dataProvider;
    }
}
