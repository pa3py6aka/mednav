<?php

namespace core\entities\Company;

use Yii;

/**
 * This is the model class for table "{{%company_categories_assignment}}".
 *
 * @property int $company_id
 * @property int $category_id
 *
 * @property CompanyCategories $category
 * @property Companies $company
 */
class CompanyCategoryAssignment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%company_categories_assignment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'category_id'], 'required'],
            [['company_id', 'category_id'], 'integer'],
            [['company_id', 'category_id'], 'unique', 'targetAttribute' => ['company_id', 'category_id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => CompanyCategories::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Companies::className(), 'targetAttribute' => ['company_id' => 'id']],
        ];
    }

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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(CompanyCategories::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Companies::className(), ['id' => 'company_id']);
    }
}
