<?php

namespace backend\forms;

use Yii;
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
            [['id', 'user_id', 'geo_id', 'main_photo_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['form', 'name', 'logo', 'slug', 'site', 'address', 'phones', 'fax', 'email', 'info', 'title', 'short_description', 'description', 'userType'], 'safe'],
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
        $query = Company::find()->with('user');

        if ($tab == 'active') {
            $query->active();
        } else if ($tab == 'moderation') {
            $query->onModeration();
        } else if ($tab == 'deleted') {
            $query->deleted();
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
            'id' => $this->id,
            'user_id' => $this->user_id,
            'geo_id' => $this->geo_id,
            'main_photo_id' => $this->main_photo_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'form', $this->form])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'site', $this->site])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'phones', $this->phones])
            ->andFilterWhere(['like', 'fax', $this->fax])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'info', $this->info])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'short_description', $this->short_description])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
