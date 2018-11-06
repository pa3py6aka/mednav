<?php

namespace core\entities\Company;

use core\entities\Geo;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%company_delivery_regions}}".
 *
 * @property int $id
 * @property int $company_id
 * @property int $country_id
 * @property int $geo_id
 *
 * @property Company $company
 * @property Geo $country
 * @property Geo $geo
 */
class CompanyDeliveryRegion extends ActiveRecord
{
    public static function create($companyId, $countryId, $geoId): CompanyDeliveryRegion
    {
        $CDRegion = new CompanyDeliveryRegion();
        $CDRegion->company_id = $companyId;
        $CDRegion->country_id = $countryId;
        $CDRegion->geo_id = $geoId;
        return $CDRegion;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%company_delivery_regions}}';
    }

    /**
     * {@inheritdoc}

    public function rules()
    {
        return [
            [['company_id', 'country_id', 'geo_id'], 'required'],
            [['company_id', 'country_id', 'geo_id'], 'integer'],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::class, 'targetAttribute' => ['company_id' => 'id']],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Geo::class, 'targetAttribute' => ['country_id' => 'id']],
            [['geo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Geo::class, 'targetAttribute' => ['geo_id' => 'id']],
        ];
    }*/

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Company ID',
            'country_id' => 'Country ID',
            'geo_id' => 'Geo ID',
        ];
    }

    public function getCompany(): ActiveQuery
    {
        return $this->hasOne(Company::class, ['id' => 'company_id']);
    }

    public function getCountry(): ActiveQuery
    {
        return $this->hasOne(Geo::class, ['id' => 'country_id']);
    }

    public function getGeo(): ActiveQuery
    {
        return $this->hasOne(Geo::class, ['id' => 'geo_id']);
    }
}
