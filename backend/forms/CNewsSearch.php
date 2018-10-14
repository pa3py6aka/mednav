<?php

namespace backend\forms;

use core\entities\CNews\CNews;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


class CNewsSearch extends CNews
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'category_id', 'indirect_links', 'main_photo_id', 'status', 'views', 'created_at', 'updated_at'], 'integer'],
            [['title', 'meta_description', 'meta_keywords', 'name', 'slug', 'intro', 'full_text'], 'safe'],
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
        $query = CNews::find()->with('user');

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
            'category_id' => $this->category_id,
            'indirect_links' => $this->indirect_links,
            'main_photo_id' => $this->main_photo_id,
            'status' => $this->status,
            'views' => $this->views,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description])
            ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'intro', $this->intro])
            ->andFilterWhere(['like', 'full_text', $this->full_text]);

        return $dataProvider;
    }
}
