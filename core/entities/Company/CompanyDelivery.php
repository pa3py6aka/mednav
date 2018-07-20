<?php

namespace core\entities\Company;

use core\entities\Geo;
use core\entities\Trade\TradeDelivery;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%company_deliveries}}".
 *
 * @property int $id
 * @property int $company_id
 * @property int $delivery_id
 * @property string $terms
 *
 * @property Company $company
 * @property TradeDelivery $delivery
 * @property CompanyDeliveryRegions[] $companyDeliveryRegions
 * @property Geo[] $geos
 */
class CompanyDelivery extends ActiveRecord
{
    public static function create($companyId, $deliveryId, $terms): CompanyDelivery
    {
        $entity = new self();
        $entity->company_id = $companyId;
        $entity->delivery_id = $deliveryId;
        $entity->terms = $terms;
        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%company_deliveries}}';
    }

    /**
     * {@inheritdoc}

    public function rules()
    {
        return [
            [['company_id', 'delivery_id', 'terms'], 'required'],
            [['company_id', 'delivery_id'], 'integer'],
            [['terms'], 'string'],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Companies::className(), 'targetAttribute' => ['company_id' => 'id']],
            [['delivery_id'], 'exist', 'skipOnError' => true, 'targetClass' => TradeDeliveries::className(), 'targetAttribute' => ['delivery_id' => 'id']],
        ];
    }*/

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Компания',
            'delivery_id' => 'Тип доставки',
            'terms' => 'Условия',
        ];
    }

    public function getCompany(): ActiveQuery
    {
        return $this->hasOne(Company::class, ['id' => 'company_id']);
    }

    public function getDelivery(): ActiveQuery
    {
        return $this->hasOne(TradeDelivery::class, ['id' => 'delivery_id']);
    }

    public function getCompanyDeliveryRegions(): ActiveQuery
    {
        return $this->hasMany(CompanyDeliveryRegions::class, ['company_deliveries_id' => 'id']);
    }

    public function getGeos(): ActiveQuery
    {
        return $this->hasMany(Geo::class, ['id' => 'geo_id'])
            ->viaTable('{{%company_deliveries_regions}}', ['company_deliveries_id' => 'id'])
            ->indexBy('id');
    }
}
