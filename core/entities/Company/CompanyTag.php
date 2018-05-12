<?php

namespace core\entities\Company;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%company_tags}}".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 *
 * @property CompanyTagsAssignment[] $companyTagsAssignments
 * @property Company[] $companies
 */
class CompanyTag extends \yii\db\ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%company_tags}}';
    }

    /**
     * {@inheritdoc}

    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
            [['name', 'slug'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['slug'], 'unique'],
        ];
    }*/

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'slug' => 'Slug',
        ];
    }

    public function getCompanyTagsAssignments(): ActiveQuery
    {
        return $this->hasMany(CompanyTagsAssignment::class, ['tag_id' => 'id']);
    }

    public function getCompanies(): ActiveQuery
    {
        return $this->hasMany(Company::class, ['id' => 'company_id'])->viaTable('{{%company_tags_assignment}}', ['tag_id' => 'id']);
    }
}
