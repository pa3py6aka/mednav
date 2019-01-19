<?php

namespace core\readModels;


use core\access\Rbac;
use core\components\Settings;
use core\entities\Brand\Brand;
use Yii;
use yii\web\NotFoundHttpException;

class BrandReadRepository extends ArticleReadCommonRepository
{
    protected $entityClass = Brand::class;
    protected $pageSizeParam = Settings::BRANDS_PAGE_SIZE;

    public function getByIdAndSlug($id, $slug): Brand
    {
        if (!$brand = Brand::find()->where(['id' => $id, 'slug' => $slug])->limit(1)->one()) {
            throw new NotFoundHttpException('Бренд не найден.');
        }

        if (!$brand->isActive() && !Yii::$app->user->can(Rbac::ROLE_MODERATOR)) {
            throw new NotFoundHttpException('Бренд не найден');
        }

        return $brand;
    }

    public function getCompanyActiveArticles($companyId)
    {
        $query = Brand::find()->where(['company_id' => $companyId])->alias('a')->active('a')->with('mainPhoto');
        return $this->getProvider($query, 25);
    }

    public function getCompanyOnModerationArticles($companyId)
    {
        $query = Brand::find()->where(['company_id' => $companyId])->alias('a')->onModeration('a')->with('mainPhoto');
        return $this->getProvider($query, 25);
    }
}