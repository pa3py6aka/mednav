<?php

namespace backend\forms;


use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\entities\Company\Company;

/**
 * CompanySearch represents the model behind the search form of `core\entities\Company\Company`.
 */
class CompanySearch extends Company
{
    public $userType;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'geo_id', 'main_photo_id', 'status'], 'integer'],
            [['form', 'user_id', 'name', 'logo', 'slug', 'site', 'address', 'phones', 'fax', 'email', 'info', 'title', 'short_description', 'description', 'userType'], 'safe'],
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
     * @param string $tab
     * @return ActiveDataProvider
     */
    public function search($params, $tab = 'active')
    {
        $query = Company::find()
            ->alias('c')
            ->joinWith('user u');

        if ($tab === 'active') {
            $query->active('c');
        } else if ($tab === 'moderation') {
            $query->onModeration('c');
        } else if ($tab === 'deleted') {
            $query->deleted('c');
        }

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

        // grid filtering conditions
        $query->andFilterWhere([
            'c.id' => $this->id,
            'c.geo_id' => $this->geo_id,
            'c.main_photo_id' => $this->main_photo_id,
            'c.status' => $this->status,
            'c.created_at' => $this->created_at,
            'c.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'c.form', $this->form])
            ->andFilterWhere(['like', 'c.name', $this->name])
            ->andFilterWhere(['like', 'c.logo', $this->logo])
            ->andFilterWhere(['like', 'c.slug', $this->slug])
            ->andFilterWhere(['like', 'c.site', $this->site])
            ->andFilterWhere(['like', 'c.address', $this->address])
            ->andFilterWhere(['like', 'c.phones', $this->phones])
            ->andFilterWhere(['like', 'c.fax', $this->fax])
            ->andFilterWhere(['like', 'c.email', $this->email])
            ->andFilterWhere(['like', 'c.info', $this->info])
            ->andFilterWhere(['like', 'c.title', $this->title])
            ->andFilterWhere(['like', 'c.short_description', $this->short_description])
            ->andFilterWhere(['like', 'c.description', $this->description])
            ->andFilterWhere([
                'or',
                [
                    'or',
                    ['like', 'u.last_name', $this->user_id],
                    ['like', 'u.name', $this->user_id],
                    ['like', 'u.patronymic', $this->user_id]
                ],
                ['c.user_id' => $this->user_id]
            ]);

        return $dataProvider;
    }
}
