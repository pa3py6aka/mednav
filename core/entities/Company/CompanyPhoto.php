<?php

namespace core\entities\Company;

use Yii;
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
class CompanyPhoto extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%company_photos}}';
    }

    /**
     * {@inheritdoc}

    public function rules()
    {
        return [
            [['company_id', 'file'], 'required'],
            [['company_id', 'sort'], 'integer'],
            [['file'], 'string', 'max' => 255],
            [['file'], 'unique'],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Companies::className(), 'targetAttribute' => ['company_id' => 'id']],
        ];
    } */

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Company ID',
            'file' => 'File',
            'sort' => 'Sort',
        ];
    }

    public function getCompany(): ActiveQuery
    {
        return $this->hasOne(Company::class, ['id' => 'company_id']);
    }
}
