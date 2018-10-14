<?php

namespace core\readModels;


use core\components\SettingsManager;
use core\entities\Article\Article;
use core\entities\CNews\CNews;
use core\entities\CNews\CNewsCategory;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class CNewsReadRepository
{
    public function getByIdAndSlug($id, $slug): CNews
    {
        if (!$news = CNews::find()->where(['id' => $id, 'slug' => $slug])->limit(1)->one()) {
            throw new NotFoundHttpException("Новость не найдена");
        }
        return $news;
    }

    public function getAllBy(CNewsCategory $category = null, $companyId = null): DataProviderInterface
    {
        $query = CNews::find()->alias('a')->active('a')->with('mainPhoto');

        if ($category) {
            $ids = ArrayHelper::merge([$category->id], $category->getDescendants()->select('id')->column());
            $query->andWhere(['a.category_id' => $ids]);
        }

        if ($companyId) {
            $query->andWhere(['company_id' => $companyId]);
        }

        $query->groupBy('a.id');
        return $this->getProvider($query);
    }

    public function getCompanyActiveArticles($companyId)
    {
        $query = Article::find()->where(['company_id' => $companyId])->alias('a')->active('a')->with('mainPhoto');
        return $this->getProvider($query, 25);
    }

    public function getCompanyOnModerationArticles($companyId)
    {
        $query = Article::find()->where(['company_id' => $companyId])->alias('a')->onModeration('a')->with('mainPhoto');
        return $this->getProvider($query, 25);
    }

    public function getCompanyActiveCNews($companyId)
    {
        $query = CNews::find()->where(['company_id' => $companyId])->alias('a')->active('a')->with('mainPhoto');
        return $this->getProvider($query, 25);
    }

    public function getCompanyOnModerationCNews($companyId)
    {
        $query = CNews::find()->where(['company_id' => $companyId])->alias('a')->onModeration('a')->with('mainPhoto');
        return $this->getProvider($query, 25);
    }

    private function getProvider(ActiveQuery $query, $default = null): ActiveDataProvider
    {
        $provider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes' => [
                    'id' => [
                        'asc' => ['a.id' => SORT_ASC],
                        'desc' => ['a.id' => SORT_DESC],
                    ],
                    'name' => [
                        'asc' => ['a.name' => SORT_ASC],
                        'desc' => ['a.name' => SORT_DESC],
                    ],
                ],
            ],
            'pagination' => [
                'pageSizeLimit' => [1, 250],
                'defaultPageSize' => !$default ? \Yii::$app->settings->get(SettingsManager::CNEWS_PAGE_SIZE) : $default,
                'forcePageParam' => false,
            ]
        ]);
        $provider->prepare(); // Для обновления данных в пагинации
        return $provider;
    }
}