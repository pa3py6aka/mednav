<?php

namespace core\forms;


use core\entities\User\User;
use yii\base\Model;

class OrderForm extends Model
{
    public $name;
    public $phone;
    public $email;
    public $address;
    public $captcha;
    public $amounts;
    public $deliveries;
    public $comments;
    public $agreement = 1;

    public function __construct(User $user = null, array $config = [])
    {
        if ($user) {
            $this->name = $user->getVisibleName();
            $this->phone = $user->getPhone();
            $this->email = $user->getEmail();
            $this->address = $user->isCompany() && $user->isCompanyActive()
                ? ($user->company->geo_id ? $user->company->geo->name . ', ' : '') . $user->company->address
                : '';
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name', 'phone', 'email', 'captcha', 'amounts', 'agreement'], 'required'],
            [['name', 'phone', 'address'], 'string'],
            ['email', 'email'],
            ['captcha', 'captcha', 'captchaAction' => '/auth/auth/captcha'],
            ['amounts', 'each', 'rule' => ['each', 'rule' => ['integer']]],
            ['deliveries', 'each', 'rule' => ['integer']],
            ['comments', 'each', 'rule' => ['string']],
            ['agreement', 'compare', 'compareValue' => 1, 'message' => "Необходимо принять пользовательское соглашение."],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'phone' => 'Телефон',
            'email' => 'E-mail',
            'address' => 'Адрес доставки',
            'captcha' => 'Код с картинки',
            'amounts' => 'Кол-во',
            'deliveries' => 'Тип доставки',
            'comments' => 'Комментарий к заказу',
            'agreement' => 'Соглашение о политике конфединциальности',
        ];
    }
}