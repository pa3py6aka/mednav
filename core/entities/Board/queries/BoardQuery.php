<?php

namespace core\entities\Board\queries;


use core\entities\Board\Board;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\core\entities\Board\Board]].
 *
 * @see \core\entities\Board\Board
 */
class BoardQuery extends ActiveQuery
{
    public function active($alias = null)
    {
        return $this->andWhere([($alias ? $alias . '.' : '') . 'status' => Board::STATUS_ACTIVE])
            ->andWhere(['>', ($alias ? $alias . '.' : '') . 'active_until', time()]);
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
