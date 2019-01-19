<?php

namespace core\readModels;


use core\access\Rbac;
use core\components\Settings;
use core\entities\Article\Article;
use Yii;
use yii\web\NotFoundHttpException;

class ArticleReadRepository extends ArticleReadCommonRepository
{
    protected $entityClass = Article::class;
    protected $pageSizeParam = Settings::ARTICLE_PAGE_SIZE;

    public function getByIdAndSlug($id, $slug): Article
    {
        if (!$article = Article::find()->where(['id' => $id, 'slug' => $slug])->limit(1)->one()) {
            throw new NotFoundHttpException('Статья не найдена');
        }

        if (!$article->isActive() && !Yii::$app->user->can(Rbac::ROLE_MODERATOR)) {
            throw new NotFoundHttpException('Статья не найдена');
        }

        return $article;
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
}