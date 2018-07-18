<?php

namespace core\entities\Trade\queries;


use core\entities\Trade\Trade;

/**
 * This is the ActiveQuery class for [[\core\entities\Trade\Trade]].
 *
 * @see \core\entities\Trade\Trade
 */
class TradeQuery extends \yii\db\ActiveQuery
{
    public function active($alias = null)
    {
        return $this->andWhere([($alias ? $alias . '.' : '') . 'status' => Trade::STATUS_ACTIVE]);
    }

    public function onModeration($alias = null)
    {
        return $this->andWhere([($alias ? $alias . '.' : '') . 'status' => Trade::STATUS_ON_PREMODERATION]);
    }

    public function deleted($alias = null)
    {
        return $this->andWhere([($alias ? $alias . '.' : '') . 'status' => Trade::STATUS_DELETED]);
    }

    public function onModerationCount($userId = null): int
    {
        if ($userId) {
            $this->where(['user_id' => $userId]);
        }
        return $this->onModeration()->cache(60)->count();
    }

    /**
     * {@inheritdoc}
     * @return \core\entities\Trade\Trade[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \core\entities\Trade\Trade|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
