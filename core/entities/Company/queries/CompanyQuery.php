<?php

namespace core\entities\Company\queries;
use core\entities\Company\Company;

/**
 * This is the ActiveQuery class for [[\core\entities\Company\Company]].
 *
 * @see \core\entities\Company\Company
 */
class CompanyQuery extends \yii\db\ActiveQuery
{
    public function active($alias = null)
    {
        return $this->andWhere([($alias ? $alias  . '.' : '') . 'status' => Company::STATUS_ACTIVE]);
    }

    public function onModeration($alias = null)
    {
        return $this->andWhere([($alias ? $alias  . '.' : '') . 'status' => Company::STATUS_ON_PREMODERATION]);
    }

    public function deleted($alias = null)
    {
        return $this->andWhere([($alias ? $alias  . '.' : '') . 'status' => Company::STATUS_DELETED]);
    }

    /**
     * {@inheritdoc}
     * @return \core\entities\Company\Company[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \core\entities\Company\Company|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
