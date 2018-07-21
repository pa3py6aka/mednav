<?php

namespace core\entities\Order;

use core\entities\Trade\Trade;
use core\entities\User\User;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%cart_items}}".
 *
 * @property int $user_id
 * @property int $trade_id
 * @property string $amount
 *
 * @property Trade $trade
 * @property User $user
 */
class CartItem extends ActiveRecord
{
    public static function create($userId, $tradeId, $amount): CartItem
    {
        $item = new self();
        $item->user_id = $userId;
        $item->trade_id = $tradeId;
        $item->amount = $amount;
        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%cart_items}}';
    }

    /**
     * {@inheritdoc}

    public function rules()
    {
        return [
            [['user_id', 'trade_id'], 'required'],
            [['user_id', 'trade_id', 'amount'], 'integer'],
            [['user_id', 'trade_id'], 'unique', 'targetAttribute' => ['user_id', 'trade_id']],
            [['trade_id'], 'exist', 'skipOnError' => true, 'targetClass' => Trades::className(), 'targetAttribute' => ['trade_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }*/

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'Пользователь',
            'trade_id' => 'Товар',
            'amount' => 'Кол-во',
        ];
    }

    public function getTrade(): ActiveQuery
    {
        return $this->hasOne(Trade::class, ['id' => 'trade_id']);
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
