<?php

namespace core\readModels;


use core\components\Settings;
use core\entities\News\News;
use yii\web\NotFoundHttpException;

class NewsReadRepository extends ArticleReadCommonRepository
{
    protected $entityClass = News::class;
    protected $pageSizeParam = Settings::NEWS_PAGE_SIZE;

    public function getByIdAndSlug($id, $slug): News
    {
        if (!$news = News::find()->where(['id' => $id, 'slug' => $slug])->limit(1)->one()) {
            throw new NotFoundHttpException("Новость не найдена");
        }
        return $news;
    }

    public function getCompanyActiveArticles($companyId)
    {
        $query = News::find()->where(['company_id' => $companyId])->alias('a')->active('a')->with('mainPhoto');
        return $this->getProvider($query, 25);
    }

    public function getCompanyOnModerationArticles($companyId)
    {
        $query = News::find()->where(['company_id' => $companyId])->alias('a')->onModeration('a')->with('mainPhoto');
        return $this->getProvider($query, 25);
    }
}