<?php

namespace core\entities\Trade;


use Yii;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $name
 * @property bool $has_terms [tinyint(1)]
 * @property bool $has_regions [tinyint(1)]
 */
class TradeDelivery extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%trade_deliveries}}';
    }
}