<?php

namespace core\entities\Order;

use core\entities\Company\Company;
use core\entities\Trade\Trade;
use core\entities\Trade\TradeDelivery;
use core\entities\User\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property int $user_order_id [int(11)]
 * @property int $for_company_id [int(11)]
 * @property int $user_id
 * @property int $delivery_id
 * @property string $comment
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property UserOrder $userOrder
 * @property Company $forCompany
 * @property OrderItem[] $orderItems
 * @property Trade[] $trades
 * @property TradeDelivery $delivery
 * @property User $user
 */
class Order extends ActiveRecord
{
    const STATUS_NEW = 0;
    const STATUS_NEW_VIEWED = 1;
    const STATUS_SENT = 5;

    public static function create($userOrderId, $forCompanyId, $userId, $deliveryId, $comment): Order
    {
        $order = new Order();
        $order->user_order_id = $userOrderId;
        $order->for_company_id = $forCompanyId;
        $order->user_id = $userId;
        $order->delivery_id = $deliveryId;
        $order->comment = $comment;
        $order->status = self::STATUS_NEW;
        return $order;
    }

    public function getNumber(): string
    {
        return $this->user_order_id . "-" . $this->id;
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

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
            'for_company_id' => 'Компания',
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

    public function getForCompany(): ActiveQuery
    {
        return $this->hasOne(Company::class, ['id' => 'for_company_id']);
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

    public function getUserOrder(): ActiveQuery
    {
        return $this->hasOne(UserOrder::class, ['id' => 'user_order_id']);
    }
}
