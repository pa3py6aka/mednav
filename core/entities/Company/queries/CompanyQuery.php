<?php

namespace core\entities\Company\queries;

/**
 * This is the ActiveQuery class for [[\core\entities\Company\Company]].
 *
 * @see \core\entities\Company\Company
 */
class CompanyQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

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
