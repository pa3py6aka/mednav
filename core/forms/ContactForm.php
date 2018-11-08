<?php

namespace core\forms;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $phone;
    //public $subject;
    public $message;
    public $captcha;
    public $agreement;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'phone', 'message'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            ['name', 'string', 'max' => 200],
            ['phone', 'string', 'max' => 20],
            ['message', 'string', 'max' => 5000],
            // verifyCode needs to be entered correctly
            ['captcha', 'required', 'message' => 'Укажите проверочный код'],
            ['captcha', 'captcha', 'captchaAction' => '/auth/auth/captcha'],
            ['agreement', 'required', 'requiredValue' => 1, 'message' => 'Вы должны подтвердить своё согласие'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'phone' => 'Телефон',
            'email' => 'Email',
            'message' => 'Сообщение',
            'captcha' => 'Проверочный код',
        ];
    }
}
