<?php

namespace core\entities\queries;


use paulzi\nestedsets\NestedSetsQueryTrait;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\core\entities\Geo]].
 *
 * @see \core\entities\Geo
 */
class GeoQuery extends ActiveQuery
{
    use NestedSetsQueryTrait;

    public function active()
    {
        return $this->andWhere(['active' => 1]);
    }

    public function notRoot()
    {
        return $this->andWhere(['>', 'depth', 0]);
    }

    public function countries()
    {
        return $this->andWhere(['depth' => 1]);
    }

    public function popular()
    {
        return $this->andWhere(['popular' => 1]);
    }

    /**
     * @inheritdoc
     * @return \core\entities\Geo[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \core\entities\Geo|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
