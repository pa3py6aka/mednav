<?php

namespace core\entities\Company;

use core\entities\Geo;
use Yii;
use yii\db\ActiveQuery;

/**
 * Это неиспользуемый теперь класс (после изменения в ТЗ)
 *
 * This is the model class for table "{{%company_deliveries_regions}}".
 *
 * @property int $company_deliveries_id
 * @property int $geo_id
 *
 * @property CompanyDelivery $companyDeliveries
 * @property Geo $geo
 */
class CompanyDeliveryRegions extends \yii\db\ActiveRecord
{
    public static function create($cdId, $geoId): CompanyDeliveryRegions
    {
        $assignment = new CompanyDeliveryRegions();
        $assignment->company_deliveries_id = $cdId;
        $assignment->geo_id = $geoId;
        return $assignment;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%company_deliveries_regions}}';
    }

    /**
     * {@inheritdoc}

    public function rules()
    {
        return [
            [['company_deliveries_id', 'geo_id'], 'required'],
            [['company_deliveries_id', 'geo_id'], 'integer'],
            [['company_deliveries_id', 'geo_id'], 'unique', 'targetAttribute' => ['company_deliveries_id', 'geo_id']],
            [['company_deliveries_id'], 'exist', 'skipOnError' => true, 'targetClass' => CompanyDeliveries::className(), 'targetAttribute' => ['company_deliveries_id' => 'id']],
            [['geo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Geo::className(), 'targetAttribute' => ['geo_id' => 'id']],
        ];
    }*/

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'company_deliveries_id' => 'Company Deliveries ID',
            'geo_id' => 'Geo ID',
        ];
    }

    public function getCompanyDeliveries(): ActiveQuery
    {
        return $this->hasOne(CompanyDelivery::class, ['id' => 'company_deliveries_id']);
    }

    public function getGeo(): ActiveQuery
    {
        return $this->hasOne(Geo::class, ['id' => 'geo_id']);
    }
}
