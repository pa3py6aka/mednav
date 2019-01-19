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
    public function active($alias = null, $withArchive = false)
    {
        $condition = [
            'and',
            [($alias ? $alias . '.' : '') . 'status' => Board::STATUS_ACTIVE],
            ['>', ($alias ? $alias . '.' : '') . 'active_until', time()]
        ];

        if ($withArchive) {
           $condition = [
               'or',
               $condition,
               $this->getArchiveCondition($alias)
           ];
        }

        return $this->andWhere($condition);
    }

    public function archive($alias = null)
    {
        return $this->andWhere($this->getArchiveCondition($alias));
    }

    private function getArchiveCondition($alias): array
    {
        return [
            'or',
            [($alias ? $alias . '.' : '') . 'status' => Board::STATUS_ARCHIVE],
            [
                'and',
                ['<=', ($alias ? $alias . '.' : '') . 'active_until', time()],
                [($alias ? $alias . '.' : '') . 'status' => Board::STATUS_ACTIVE]
            ]
        ];
    }

    public function onModeration($alias = null)
    {
        return $this->andWhere([($alias ? $alias . '.' : '') . 'status' => Board::STATUS_ON_MODERATION]);
    }

    public function notDeleted($alias = null)
    {
        return $this->andWhere(['not', [($alias ? $alias . '.' : '') . 'status' => Board::STATUS_DELETED]]);
    }

    public function deleted($alias = null)
    {
        return $this->andWhere([($alias ? $alias . '.' : '') . 'status' => Board::STATUS_DELETED]);
    }

    public function toExtend()
    {
        return $this->active()->andWhere(['<', 'notification_date', time()]);
    }

    /**
     * @inheritdoc
     * @return \core\entities\Board\Board[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \core\entities\Board\Board|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @inheritdoc
     * @return array|Board[]|\yii\db\BatchQueryResult
     */
    public function each($batchSize = 100, $db = null)
    {
        return parent::each($batchSize, $db);
    }
}
