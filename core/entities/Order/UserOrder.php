<?php

namespace core\entities\Order;

use core\entities\User\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%user_orders}}".
 *
 * @property int $id
 * @property int $user_id
 * @property string $user_name
 * @property string $user_phone
 * @property string $user_email
 * @property string $address
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Order[] $orders
 * @property User $user
 */
class UserOrder extends \yii\db\ActiveRecord
{
    public static function create($userId, $name, $phone, $email, $address): UserOrder
    {
        $userOrder = new self();
        $userOrder->user_id = $userId;
        $userOrder->user_name = $name;
        $userOrder->user_phone = $phone;
        $userOrder->user_email = $email;
        $userOrder->address = $address;
        $userOrder->status = Order::STATUS_NEW;
        return $userOrder;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%user_orders}}';
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}

    public function rules()
    {
        return [
            [['user_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['address', 'status', 'created_at', 'updated_at'], 'required'],
            [['address'], 'string'],
            [['user_name', 'user_phone', 'user_email'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }*/

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'user_name' => 'User Name',
            'user_phone' => 'User Phone',
            'user_email' => 'User Email',
            'address' => 'Address',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getOrders(): ActiveQuery
    {
        return $this->hasMany(Order::class, ['user_order_id' => 'id']);
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
