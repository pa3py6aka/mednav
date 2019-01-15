<?php

namespace backend\forms;


use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\entities\User\User;

/**
 * UserSearch represents the model behind the search form of `core\entities\User\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'last_online', 'status', 'updated_at'], 'integer'],
            [['email', 'auth_key', 'password_hash', 'password_reset_token'], 'safe'],
            ['created_at', 'date', 'format' => 'php:d.m.Y'],
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
     * @param string $tab
     * @return ActiveDataProvider
     */
    public function search($params, $tab = 'active')
    {
        $query = User::find()
            ->alias('u');

        if ($tab === 'active') {
            $query->active('u');
        } else if ($tab === 'moderation') {
            $query->onModeration('u');
        } else if ($tab === 'deleted') {
            $query->deleted('u');
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

        if ($this->created_at) {
            $from = strtotime($this->created_at . ' 0:00');
            $to = strtotime($this->created_at . ' 23:59');
            $query->andWhere(['between', 'created_at', $from, $to]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'type' => $this->type,
            'last_online' => $this->last_online,
            'status' => $this->status,
            //'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token]);

        return $dataProvider;
    }
}
