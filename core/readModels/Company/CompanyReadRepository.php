<?php

namespace core\readModels\Company;


use core\entities\Company\Company;
use core\entities\Company\CompanyCategory;
use core\entities\Company\CompanyCategoryAssignment;
use core\entities\Geo;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class CompanyReadRepository
{
    public function getByIdAndSlug($id, $slug): Company
    {
        if (!$company = Company::find()->where(['id' => $id, 'slug' => $slug])->limit(1)->one()) {
            throw new NotFoundHttpException("Объявление не найдено");
        }
        return $company;
    }

    public function getAllBy(CompanyCategory $category = null, Geo $geo = null): DataProviderInterface
    {
        $query = Company::find()->alias('c')->active('c')->with('mainPhoto', 'geo', 'boards', 'trades');

        if ($category) {
            $ids = ArrayHelper::merge([$category->id], $category->getDescendants()->select('id')->column());
            $query->leftJoin(CompanyCategoryAssignment::tableName() . ' cca', 'c.id=cca.company_id');
            $query->andWhere(['cca.company_id' => $ids]);
        }

        if ($geo) {
            $ids = ArrayHelper::merge([$geo->id], $geo->getDescendants()->select('id')->column());
            $query->andWhere(['c.geo_id' => $ids]);
        }

        $query->groupBy('c.id');
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
                        'asc' => ['c.name' => SORT_ASC],
                        'desc' => ['c.name' => SORT_DESC],
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