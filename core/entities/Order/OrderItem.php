<?php

namespace core\entities\Order;

use core\entities\Trade\Trade;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "order_items".
 *
 * @property int $order_id
 * @property int $trade_id
 * @property int $amount
 *
 * @property Order $order
 * @property Trade $trade
 */
class OrderItem extends ActiveRecord
{
    public static function create($orderId, $tradeId, $amount): OrderItem
    {
        $item = new self();
        $item->order_id = $orderId;
        $item->trade_id = $tradeId;
        $item->amount = $amount;
        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_items}}';
    }

    /**
     * {@inheritdoc}

    public function rules()
    {
        return [
            [['order_id', 'trade_id'], 'required'],
            [['order_id', 'trade_id', 'amount'], 'integer'],
            [['order_id', 'trade_id'], 'unique', 'targetAttribute' => ['order_id', 'trade_id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['trade_id'], 'exist', 'skipOnError' => true, 'targetClass' => Trades::className(), 'targetAttribute' => ['trade_id' => 'id']],
        ];
    }*/

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'order_id' => '№ заказа',
            'trade_id' => 'Товар',
            'amount' => 'Кол-во',
        ];
    }

    public function getOrder(): ActiveQuery
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }

    public function getTrade(): ActiveQuery
    {
        return $this->hasOne(Trade::class, ['id' => 'trade_id']);
    }
}
