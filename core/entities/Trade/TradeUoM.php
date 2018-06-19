<?php

namespace core\entities\Trade;


use Yii;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $name
 * @property string $sign
 * @property int $default
 */
class TradeUoM extends ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->get('sqlite');
    }

    public static function tableName()
    {
        return 'trade_uoms';
    }

    public static function getDefaultId(): int
    {
        return self::find()->where(['default' => 1])->one()->id;
    }
}