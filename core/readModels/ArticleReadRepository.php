<?php

namespace core\readModels;


use core\entities\Article\Article;
use core\entities\Article\ArticleCategory;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class ArticleReadRepository
{
    public function getByIdAndSlug($id, $slug): Article
    {
        if (!$article = Article::find()->where(['id' => $id, 'slug' => $slug])->limit(1)->one()) {
            throw new NotFoundHttpException("Статья не найдена");
        }
        return $article;
    }

    public function getAllBy(ArticleCategory $category = null): DataProviderInterface
    {
        $query = Article::find()->alias('a')->active('a')->with('mainPhoto');

        if ($category) {
            $ids = ArrayHelper::merge([$category->id], $category->getDescendants()->select('id')->column());
            $query->andWhere(['a.category_id' => $ids]);
        }

        $query->groupBy('a.id');
        return $this->getProvider($query);
    }

    public function getCompanyActiveArticles($companyId)
    {
        $query = Article::find()->where(['company_id' => $companyId])->alias('a')->active('a')->with('mainPhoto');
        return $this->getProvider($query);
    }

    public function getCompanyOnModerationArticles($companyId)
    {
        $query = Article::find()->where(['company_id' => $companyId])->alias('a')->onModeration('a')->with('mainPhoto');
        return $this->getProvider($query);
    }

    private function getProvider(ActiveQuery $query): ActiveDataProvider
    {
        $provider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['name' => SORT_ASC],
                'attributes' => [
                    'name' => [
                        'asc' => ['a.name' => SORT_ASC],
                        'desc' => ['a.name' => SORT_DESC],
                    ],
                ],
            ],
            'pagination' => [
                'pageSizeLimit' => [5, 250], //Todo Выставить 25 на продакшине
                'defaultPageSize' => 5,
                'forcePageParam' => false,
            ]
        ]);
        $provider->models; // Для обновления данных в пагинации
        return $provider;
    }
}