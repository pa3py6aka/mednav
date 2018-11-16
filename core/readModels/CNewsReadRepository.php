<?php

namespace core\readModels;


use core\components\Settings;
use core\entities\Article\Article;
use core\entities\CNews\CNews;
use yii\web\NotFoundHttpException;

class CNewsReadRepository extends ArticleReadCommonRepository
{
    protected $entityClass = CNews::class;
    protected $pageSizeParam = Settings::CNEWS_PAGE_SIZE;

    public function getByIdAndSlug($id, $slug): CNews
    {
        if (!$news = CNews::find()->where(['id' => $id, 'slug' => $slug])->limit(1)->one()) {
            throw new NotFoundHttpException("Новость не найдена");
        }
        return $news;
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
}