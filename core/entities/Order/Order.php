<?php

namespace core\entities\Order;

use core\entities\Trade\Trade;
use core\entities\Trade\TradeDelivery;
use core\entities\User\User;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property int $user_id
 * @property int $delivery_id
 * @property string $comment
 * @property string $user_name
 * @property string $user_phone
 * @property string $user_email
 * @property string $address
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property OrderItem[] $orderItems
 * @property Trade[] $trades
 * @property TradeDelivery $delivery
 * @property User $user
 */
class Order extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%orders}}';
    }

    /**
     * {@inheritdoc}

    public function rules()
    {
        return [
            [['user_id', 'comment', 'address', 'status', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'delivery_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['comment', 'address'], 'string'],
            [['user_name', 'user_phone', 'user_email'], 'string', 'max' => 255],
        ];
    } */

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Заказчик',
            'delivery_id' => 'Тип доставки',
            'comment' => 'Комментарий',
            'user_name' => 'ФИО/Компания',
            'user_phone' => 'Телефон',
            'user_email' => 'Email',
            'address' => 'Адрес',
            'status' => 'Статус',
            'created_at' => 'Дата заказа',
            'updated_at' => 'Дата обновления',
        ];
    }

    public function getOrderItems(): ActiveQuery
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
    }

    public function getTrades(): ActiveQuery
    {
        return $this->hasMany(Trade::class, ['id' => 'trade_id'])->viaTable('order_items', ['order_id' => 'id']);
    }

    public function getDelivery(): ActiveQuery
    {
        return $this->hasOne(TradeDelivery::class, ['id' => 'delivery_id']);
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
