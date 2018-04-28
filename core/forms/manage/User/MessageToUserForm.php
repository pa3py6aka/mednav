<?php

namespace core\forms\manage\User;


use yii\base\Model;

class MessageToUserForm extends Model
{
    public $message;

    public function rules()
    {
        return [
            ['message', 'required'],
            ['message', 'trim'],
            ['message', 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'message' => 'Текст сообщения',
        ];
    }
}