<?php

namespace backend\forms;


use core\entities\Article\common\ArticleCommon;
use yii\data\ActiveDataProvider;

/**
 * Trait ArticleSearchTrait
 *
 * @property ArticleCommon $entityClass
 */
trait ArticleSearchTrait
{
    public $userType;
    public $category;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'indirect_links', 'main_photo_id', 'status', 'views', 'created_at', 'updated_at'], 'integer'],
            [['title', 'meta_description', 'meta_keywords', 'name', 'slug', 'intro', 'full_text', 'userType', 'category', 'user_id'], 'safe'],
        ];
    }

    public function search($params, $tab = 'active')
    {
        $query = $this->entityClass::find()
            ->alias('a')
            ->with('user');

        if ($tab == 'active') {
            $query->active('a');
        } else if ($tab == 'moderation') {
            $query->onModeration('a');
        } else if ($tab == 'deleted') {
            $query->deleted('a');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if ($this->userType) {
            $query->joinWith('user u');
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'a.id' => $this->id,
            'a.user_id' => $this->user_id,
            'a.category_id' => $this->category_id,
            'a.indirect_links' => $this->indirect_links,
            'a.main_photo_id' => $this->main_photo_id,
            'a.status' => $this->status,
            'a.views' => $this->views,
            'a.created_at' => $this->created_at,
            'a.updated_at' => $this->updated_at,
            'u.type' => $this->userType,
        ]);

        $query->andFilterWhere(['like', 'a.title', $this->title])
            ->andFilterWhere(['like', 'a.meta_description', $this->meta_description])
            ->andFilterWhere(['like', 'a.meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['like', 'a.name', $this->name])
            ->andFilterWhere(['like', 'a.slug', $this->slug])
            ->andFilterWhere(['like', 'a.intro', $this->intro])
            ->andFilterWhere(['like', 'a.full_text', $this->full_text]);

        return $dataProvider;
    }
}