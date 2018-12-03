<?php

namespace core\entities\Dialog;

use core\entities\User\User;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%dialogs}}".
 *
 * @property int $id
 * @property int $user_from
 * @property int $user_to
 * @property string $subject [varchar(255)]
 * @property string $name [varchar(255)]
 * @property string $phone [varchar(255)]
 * @property string $email [varchar(255)]
 * @property int $status [tinyint(3)]
 *
 * @property User $userFrom
 * @property User $userTo
 * @property Message[] $messages
 * @property Message $lastMessage
 */
class Dialog extends ActiveRecord
{
    public $not_read;

    const STATUS_ACTIVE = 1;
    const STATUS_ARCHIVE = 5;

    public static function create($userFromId, $userToId, $subject, $name = '', $phone = '', $email = ''): Dialog
    {
        $dialog = new self();
        $dialog->user_from = $userFromId;
        $dialog->user_to = $userToId;
        $dialog->subject = $subject;
        $dialog->name = $name;
        $dialog->phone = $phone;
        $dialog->email = $email;
        $dialog->status = self::STATUS_ACTIVE;
        return $dialog;
    }

    public function getDialogName(): string
    {
        return $this->subject ? Html::encode($this->subject) : 'Переписка';
        //$firstName = $this->user_one ? $this->userOne->getVisibleName() : "Посетитель сайта";
        //$secondName = $this->user_two ? $this->userTwo->getVisibleName() : "Посетитель сайта";
        //return $firstName . ' - ' . $secondName;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%dialogs}}';
    }

    /**
     * {@inheritdoc}

    public function rules()
    {
        return [
            [['user_one', 'user_two'], 'integer'],
            [['user_one'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_one' => 'id']],
            [['user_two'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_two' => 'id']],
        ];
    } */

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_from' => 'От кого',
            'user_to' => 'Кому',
        ];
    }

    /**
     * @param integer $userId Передаём сюда ID пользователя, и получаем ID пользователя-собеседника
     * @return int
     */
    public function getInterlocutorId($userId)
    {
        return $userId === $this->user_from ? $this->user_to : $this->user_from;
    }

    /**
     * @param integer $userId Передаём сюда ID пользователя, и получаем пользователя-собеседника
     * @return User
     */
    public function getInterlocutor($userId)
    {
        return $userId === $this->user_from ? $this->userTo : $this->userFrom;
    }

    public function getUserFrom(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_from']);
    }

    public function getUserTo(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_to']);
    }

    public function getMessages(): ActiveQuery
    {
        return $this->hasMany(Message::class, ['dialog_id' => 'id']);
    }

    public function getLastMessage()
    {
        return $this->hasOne(Message::class, ['dialog_id' => 'id'])
            ->orderBy(['created_at' => SORT_DESC]);
    }
}
