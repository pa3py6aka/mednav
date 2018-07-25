<?php

namespace core\entities\Dialog;

use core\entities\User\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%messages}}".
 *
 * @property int $id
 * @property int $dialog_id
 * @property int $user_id
 * @property string $text
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Dialog $dialog
 * @property User $user
 */
class Message extends ActiveRecord
{
    public static function create($dialogId, $userId, $text): Message
    {
        $message = new Message();
        $message->dialog_id = $dialogId;
        $message->user_id = $userId;
        $message->text = $text;
        $message->status = 0;
        return $message;
    }

    public function isMy(): bool
    {
        return Yii::$app->user->id === $this->user_id;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%messages}}';
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
            [['dialog_id', 'text', 'created_at', 'updated_at'], 'required'],
            [['dialog_id', 'user_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['text'], 'string'],
            [['name', 'phone', 'email'], 'string', 'max' => 255],
            [['dialog_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dialogs::className(), 'targetAttribute' => ['dialog_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    } */

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dialog_id' => 'Dialog ID',
            'user_id' => 'User ID',
            'text' => 'Text',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getDialog(): ActiveQuery
    {
        return $this->hasOne(Dialog::class, ['id' => 'dialog_id']);
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
