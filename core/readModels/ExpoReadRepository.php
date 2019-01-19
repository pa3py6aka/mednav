<?php

namespace core\readModels;


use core\access\Rbac;
use core\components\Settings;
use core\entities\Expo\Expo;
use Yii;
use yii\web\NotFoundHttpException;

class ExpoReadRepository extends ArticleReadCommonRepository
{
    protected $entityClass = Expo::class;
    protected $pageSizeParam = Settings::EXPO_PAGE_SIZE;

    public function getByIdAndSlug($id, $slug): Expo
    {
        if (!$expo = Expo::find()->where(['id' => $id, 'slug' => $slug])->limit(1)->one()) {
            throw new NotFoundHttpException('Выставка не найдена.');
        }

        if (!$expo->isActive() && !Yii::$app->user->can(Rbac::ROLE_MODERATOR)) {
            throw new NotFoundHttpException('Выставка не найдена');
        }

        return $expo;
    }

    public function getCompanyActiveExpos($companyId)
    {
        $query = Expo::find()->where(['company_id' => $companyId])->alias('a')->active('a')->with('mainPhoto');
        return $this->getProvider($query, 25);
    }

    public function getCompanyOnModerationExpos($companyId)
    {
        $query = Expo::find()->where(['company_id' => $companyId])->alias('a')->onModeration('a')->with('mainPhoto');
        return $this->getProvider($query, 25);
    }
}