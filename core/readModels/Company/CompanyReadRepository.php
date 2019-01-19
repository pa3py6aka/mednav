<?php

namespace core\readModels\Company;


use core\access\Rbac;
use core\components\Settings;
use core\entities\Company\Company;
use core\entities\Company\CompanyCategory;
use core\entities\Company\CompanyCategoryAssignment;
use core\entities\Geo;
use Yii;
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
            throw new NotFoundHttpException('Компания не найдена');
        }

        if (!$company->isActive() && !Yii::$app->user->can(Rbac::ROLE_MODERATOR)) {
            throw new NotFoundHttpException('Компания не найдена');
        }

        return $company;
    }

    public function getById($id): Company
    {
        if (!$company = Company::find()->where(['id' => $id])->limit(1)->one()) {
            throw new NotFoundHttpException('Компания не найдена');
        }
        return $company;
    }

    public function getAllBy(CompanyCategory $category = null, Geo $geo = null, $search = null): DataProviderInterface
    {
        $query = Company::find()->alias('c')->active('c')->with('mainPhoto', 'geo', 'boards', 'trades', 'cNews', 'articles');

        if ($category) {
            $ids = ArrayHelper::merge([$category->id], $category->getDescendants()->select('id')->column());
            $query->leftJoin(CompanyCategoryAssignment::tableName() . ' cca', 'c.id=cca.company_id');
            $query->andWhere(['cca.category_id' => $ids]);
        }

        if ($geo) {
            $ids = ArrayHelper::merge([$geo->id], $geo->getDescendants()->select('id')->column());
            $query->andWhere(['c.geo_id' => $ids]);
        }

        if ($search) {
            $query->andWhere(['like', 'c.name', $search]);
        }

        $query->groupBy('c.id');
        return $this->getProvider($query);
    }

    private function getProvider(ActiveQuery $query): ActiveDataProvider
    {
        $provider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes' => [
                    'name' => [
                        'asc' => ['c.name' => SORT_ASC],
                        'desc' => ['c.name' => SORT_DESC],
                    ],
                    'id' => [
                        'asc' => ['c.id' => SORT_ASC],
                        'desc' => ['c.id' => SORT_DESC],
                    ]
                ],
            ],
            'pagination' => [
                'pageSizeLimit' => [1, 250], //Todo Выставить 25 на продакшине
                'defaultPageSize' => Yii::$app->settings->get(Settings::COMPANY_PAGE_SIZE),
                'forcePageParam' => false,
            ]
        ]);
        $provider->models; // Для обновления данных в пагинации
        return $provider;
    }
}