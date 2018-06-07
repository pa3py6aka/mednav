<?php

namespace core\entities\Company;

use core\entities\PhotoInterface;
use core\entities\PhotoTrait;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%company_photos}}".
 *
 * @property int $id
 * @property int $company_id
 * @property string $file
 * @property int $sort
 *
 * @property Company $company
 */
class CompanyPhoto extends ActiveRecord implements PhotoInterface
{
    use PhotoTrait;

    public static function create($companyId, $file, $sort): CompanyPhoto
    {
        $photo = new static();
        $photo->company_id = $companyId;
        $photo->file = $file;
        $photo->sort = $sort;
        return $photo;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%company_photos}}';
    }

    public function getCompany(): ActiveQuery
    {
        return $this->hasOne(Company::class, ['id' => 'company_id']);
    }
}
