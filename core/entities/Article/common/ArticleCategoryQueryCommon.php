<?php

namespace core\entities\Article\common;


use paulzi\nestedsets\NestedSetsQueryTrait;

/**
 * This is the ActiveQuery class for [[\core\entities\Article\ArticleCategory]].
 *
 * @see \core\entities\Article\ArticleCategory
 */
class ArticleCategoryQueryCommon extends \yii\db\ActiveQuery
{
    //use NestedSetsQueryTrait;

    public function roots()
    {
        return $this->andWhere(['depth' => 1])->orderBy(['lft' => SORT_ASC]);
    }

    public function active()
    {
        return $this->andWhere(['active' => 1]);
    }

    public function enabled()
    {
        return $this->andWhere(['enabled' => 1]);
    }

    /**
     * {@inheritdoc}
     * @return \core\entities\Article\ArticleCategory[]|\core\entities\News\NewsCategory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \core\entities\Article\ArticleCategory|\core\entities\News\NewsCategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
