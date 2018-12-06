<?php

namespace core\readModels;


use core\entities\Page;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class PageReadRepository
{
    public function getBySlug($slug): Page
    {
        if (!$page = Page::find()->where(['slug' => $slug])->limit(1)->one()) {
            throw new NotFoundHttpException('Статья не найдена.');
        }
        return $page;
    }

    public function getPagesLinks(): array
    {
        return ArrayHelper::map(Page::find()->asArray()->all(), 'slug', 'name');
    }
}