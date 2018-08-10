<?php

namespace core\entities\Article\queries;


use core\entities\Article\Article;

/**
 * This is the ActiveQuery class for [[\core\entities\Article\Article]].
 *
 * @see \core\entities\Article\Article
 */
class ArticlesQuery extends \yii\db\ActiveQuery
{
    public function active($alias = null)
    {
        return $this->andWhere([($alias ? $alias  . '.' : '') . 'status' => Article::STATUS_ACTIVE]);
    }

    public function onModeration($alias = null)
    {
        return $this->andWhere([($alias ? $alias  . '.' : '') . 'status' => Article::STATUS_ON_PREMODERATION]);
    }

    public function deleted($alias = null)
    {
        return $this->andWhere([($alias ? $alias  . '.' : '') . 'status' => Article::STATUS_DELETED]);
    }

    /**
     * {@inheritdoc}
     * @return \core\entities\Article\Article[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \core\entities\Article\Article|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
