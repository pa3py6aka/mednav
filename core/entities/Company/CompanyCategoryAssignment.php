<?php

namespace core\entities\Company;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%company_categories_assignment}}".
 *
 * @property int $company_id
 * @property int $category_id
 *
 * @property CompanyCategory $category
 * @property Company $company
 */
class CompanyCategoryAssignment extends \yii\db\ActiveRecord
{
    public static function create($companyId, $categoryId): CompanyCategoryAssignment
    {
        $assignment = new static();
        $assignment->company_id = $companyId;
        $assignment->category_id = $categoryId;
        return $assignment;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%company_categories_assignment}}';
    }

    /**
     * {@inheritdoc}

    public function rules()
    {
        return [
            [['company_id', 'category_id'], 'required'],
            [['company_id', 'category_id'], 'integer'],
            [['company_id', 'category_id'], 'unique', 'targetAttribute' => ['company_id', 'category_id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => CompanyCategories::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Companies::className(), 'targetAttribute' => ['company_id' => 'id']],
        ];
    }*/

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'company_id' => 'Company ID',
            'category_id' => 'Category ID',
        ];
    }

    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(CompanyCategory::class, ['id' => 'category_id']);
    }

    public function getCompany(): ActiveQuery
    {
        return $this->hasOne(Company::class, ['id' => 'company_id']);
    }
}
