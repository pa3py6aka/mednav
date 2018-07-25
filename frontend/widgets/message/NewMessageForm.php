<?php

namespace frontend\widgets\message;


use yii\base\Model;

class NewMessageForm extends Model
{
    public $subject;
    public $text;
    public $name;
    public $email;
    public $phone;
    public $captcha;
    public $toId;

    public function rules()
    {
        return [
            [['subject', 'text', 'name', 'email', 'phone'], 'trim'],

            [['subject', 'text', 'toId'], 'required'],
            ['text', 'string', 'max' => 3000],
            ['subject', 'string', 'max' => 255],

            [['name', 'email', 'captcha'], 'required', 'when' => function ($model) {
                return \Yii::$app->user->isGuest;
            }, 'message' => ''],

            [['name', 'phone'], 'string', 'max' => 255],
            ['email', 'email', 'message' => ''],
            ['captcha', 'captcha', 'captchaAction' => '/auth/auth/captcha', 'when' => function ($model) {
                return \Yii::$app->user->isGuest;
            }],

            ['toId', 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'subject' => 'Тема',
            'toId' => 'Кому',
            'text' => 'Сообщение',
            'name' => 'Имя',
            'email' => 'E-mail',
            'phone' => 'Телефон',
            'captcha' => 'Код с картинки',
        ];
    }

}