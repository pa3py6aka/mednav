<?php

namespace core\entities\Board\queries;


use paulzi\nestedsets\NestedSetsQueryTrait;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\core\entities\Board\BoardCategory]].
 *
 * @see \core\entities\Board\BoardCategory
 */
class BoardCategoryQuery extends ActiveQuery
{
    //use NestedSetsQueryTrait;

    public function roots()
    {
        return $this->andWhere(['depth' => 1]);
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
     * @inheritdoc
     * @return \core\entities\Board\BoardCategory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \core\entities\Board\BoardCategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
