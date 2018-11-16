<?php

namespace core\readModels;


use core\entities\Article\common\ArticleCommon;
use core\entities\CategoryInterface;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class ArticleReadCommonRepository
{
    /* @var ArticleCommon */
    protected $entityClass = null;

    protected $pageSizeParam = 25;

    public function getAllBy(CategoryInterface $category = null, $companyId = null, $search = null): DataProviderInterface
    {
        $query = $this->entityClass::find()->alias('a')->active('a')->with('mainPhoto');

        if ($category) {
            $ids = ArrayHelper::merge([$category->id], $category->getDescendants()->select('id')->column());
            $query->andWhere(['a.category_id' => $ids]);
        }

        if ($companyId) {
            $query->andWhere(['company_id' => $companyId]);
        }

        if ($search) {
            $query->andWhere(['like', 'a.name', $search]);
        }

        $query->groupBy('a.id');
        return $this->getProvider($query);
    }

    protected function getProvider(ActiveQuery $query, $default = null): ActiveDataProvider
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
                'defaultPageSize' => !$default ? Yii::$app->settings->get($this->pageSizeParam) : $default,
                'forcePageParam' => false,
            ]
        ]);
        $provider->prepare(); // Для обновления данных в пагинации
        return $provider;
    }
}