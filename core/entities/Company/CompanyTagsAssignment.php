<?php

namespace core\entities\Company;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%company_tags_assignment}}".
 *
 * @property int $company_id
 * @property int $tag_id
 *
 * @property Company $company
 * @property CompanyTag $tag
 */
class CompanyTagsAssignment extends ActiveRecord
{
    public static function create($companyId, $tagId): CompanyTagsAssignment
    {
        $assignment = new static();
        $assignment->company_id = $companyId;
        $assignment->tag_id = $tagId;
        return $assignment;
    }

    public static function tableName(): string
    {
        return '{{%company_tags_assignment}}';
    }

    /**
     * {@inheritdoc}

    public function rules()
    {
        return [
            [['company_id', 'tag_id'], 'required'],
            [['company_id', 'tag_id'], 'integer'],
            [['company_id', 'tag_id'], 'unique', 'targetAttribute' => ['company_id', 'tag_id']],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Companies::className(), 'targetAttribute' => ['company_id' => 'id']],
            [['tag_id'], 'exist', 'skipOnError' => true, 'targetClass' => CompanyTags::className(), 'targetAttribute' => ['tag_id' => 'id']],
        ];
    }*/

    public function attributeLabels(): array
    {
        return [
            'company_id' => 'Company ID',
            'tag_id' => 'Tag ID',
        ];
    }

    public function getCompany(): ActiveQuery
    {
        return $this->hasOne(Company::class, ['id' => 'company_id']);
    }

    public function getTag(): ActiveQuery
    {
        return $this->hasOne(CompanyTag::class, ['id' => 'tag_id']);
    }
}
