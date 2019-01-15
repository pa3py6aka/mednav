<?php

namespace backend\forms;

use core\entities\Company\Company;
use core\entities\User\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\entities\Trade\TradeUserCategory;

/**
 * TradeUserCategorySearch represents the model behind the search form of `core\entities\Trade\TradeUserCategory`.
 */
class TradeUserCategorySearch extends TradeUserCategory
{
    public $category;
    public $user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'category_id', 'uom_id', 'currency_id', 'wholesale', 'category'], 'integer'],
            [['name', 'user'], 'safe'],
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
        $query = TradeUserCategory::find()
            ->alias('tuc')
            ->with('user.company', 'category', 'trades');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if ($this->user) {
            $query->leftJoin(Company::tableName() . ' c', 'c.user_id=tuc.user_id');
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tuc.id' => $this->id,
            'tuc.user_id' => $this->user_id,
            'tuc.category_id' => $this->category,
            'tuc.uom_id' => $this->uom_id,
            'tuc.currency_id' => $this->currency_id,
            'tuc.wholesale' => $this->wholesale,
        ]);

        $query->andFilterWhere(['like', 'tuc.name', $this->name]);

        if ($this->user) {
            $query->andWhere([
                'or',
                ['c.id' => $this->user],
                ['like', 'c.email', $this->user],
                ['like', 'c.name', $this->user],
            ]);
        }

        return $dataProvider;
    }
}
