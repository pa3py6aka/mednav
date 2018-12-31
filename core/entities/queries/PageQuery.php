<?php

namespace core\entities\queries;


use core\entities\Page;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\core\entities\Page]].
 *
 * @see \core\entities\Page
 */
class PageQuery extends ActiveQuery
{
    public function forUCP()
    {
        return $this->andWhere(['type' => Page::TYPE_UCP_PAGE]);
    }

    public function forFront()
    {
        return $this->andWhere(['type' => Page::TYPE_FRONT_PAGE]);
    }

    /**
     * @inheritdoc
     * @return \core\entities\Page[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \core\entities\Page|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
