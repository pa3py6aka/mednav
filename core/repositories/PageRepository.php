<?php

namespace core\repositories;


use core\entities\Page;
use yii\web\NotFoundHttpException;

class PageRepository
{
    public function get($id): Page
    {
        return $this->getBy(['id' => $id]);
    }

    public function save(Page $page): void
    {
        if (!$page->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function remove(Page $page): void
    {
        if (!$page->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

    public function massRemove(array $ids): int
    {
        return Page::deleteAll(['id' => $ids]);
    }

    private function getBy(array $condition): Page
    {
        if (!$page = Page::find()->andWhere($condition)->limit(1)->one()) {
            throw new NotFoundHttpException('Статья не найдена.');
        }
        return $page;
    }
}