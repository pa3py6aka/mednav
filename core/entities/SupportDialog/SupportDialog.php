<?php

namespace core\entities\SupportDialog;

use core\entities\User\User;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%support_dialogs}}".
 *
 * @property int $id
 * @property int $user_id
 * @property string $subject
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property int $status
 *
 * @property User $user
 * @property SupportMessage[] $messages
 * @property SupportMessage $lastMessage
 */
class SupportDialog extends ActiveRecord
{
    public $not_read;
    public $max;

    public const STATUS_ACTIVE = 1;
    public const STATUS_ARCHIVE = 5;

    public static function create($userId, $subject, $name = '', $phone = '', $email = ''): SupportDialog
    {
        $dialog = new self();
        $dialog->user_id = $userId;
        $dialog->subject = $subject;
        $dialog->name = $name;
        $dialog->phone = $phone;
        $dialog->email = $email;
        $dialog->status = self::STATUS_ACTIVE;
        return $dialog;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%support_dialogs}}';
    }

    /**
     * {@inheritdoc}

    public function rules()
    {
        return [
            [['user_id', 'status'], 'integer'],
            [['subject', 'name', 'phone', 'email'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }*/

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'subject' => 'Subject',
            'name' => 'Name',
            'phone' => 'Phone',
            'email' => 'Email',
            'status' => 'Status',
        ];
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getMessages(): ActiveQuery
    {
        return $this->hasMany(SupportMessage::class, ['dialog_id' => 'id']);
    }

    public function getLastMessage(): ActiveQuery
    {
        return $this->hasOne(SupportMessage::class, ['dialog_id' => 'id'])
            ->orderBy(['created_at' => SORT_DESC]);
    }
}
