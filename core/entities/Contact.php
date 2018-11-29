<?php

namespace core\entities;

use core\entities\User\User;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%contacts}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int $contact_id
 *
 * @property User $contact
 * @property User $user
 */
class Contact extends ActiveRecord
{
    public static function create($userId, $contactId): Contact
    {
        $contact = new self();
        $contact->user_id = $userId;
        $contact->contact_id = $contactId;
        return $contact;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%contacts}}';
    }

    /**
     * {@inheritdoc}

    public function rules()
    {
        return [
            [['user_id', 'contact_id'], 'required'],
            [['user_id', 'contact_id'], 'integer'],
            [['user_id', 'contact_id'], 'unique', 'targetAttribute' => ['user_id', 'contact_id']],
            [['contact_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['contact_id' => 'id']],
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
            'contact_id' => 'Contact ID',
        ];
    }

    public function getContact(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'contact_id']);
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
