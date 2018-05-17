<?php

namespace core\forms\User;


use core\entities\Geo;
use core\entities\User\User;
use yii\base\Model;

class UserProfileForm extends Model
{
    public $email;
    public $last_name;
    public $name;
    public $patronymic;
    public $gender;
    public $birthday;
    public $phone;
    public $site;
    public $skype;
    public $organization;
    public $geoId;

    private $_user;

    public function __construct(User $user, array $config = [])
    {
        $this->email = $user->email;
        $this->last_name = $user->last_name;
        $this->name = $user->name;
        $this->patronymic = $user->patronymic;
        $this->gender = $user->gender;
        $this->birthday = $user->birthday ? date('d.m.Y', strtotime($user->birthday)) : '';
        $this->phone = $user->phone;
        $this->site = $user->site;
        $this->skype = $user->skype;
        $this->organization = $user->organization;
        $this->geoId = $user->geo_id;

        $this->_user = $user;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['email', 'name', 'geoId'], 'required'],
            /*['geoId', 'required', 'when' => function ($model) {
                return !$this->_user->isCompany();
            }, 'whenClient' => "function (attribute, value) {
                return " . ($this->_user->isCompany() ? "null" : "1") . ";
            }"],*/

            ['geoId', 'integer'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class, 'filter' => ['<>', 'id', $this->_user->id]],
            [['last_name', 'name', 'patronymic', 'site', 'skype', 'organization'], 'string', 'max' => 255],
            ['gender', 'in', 'range' => array_keys(User::gendersArray())],
            ['birthday', 'date', 'format' => 'php: d.m.Y'],
            ['phone', 'string', 'max' => 25],

            [['last_name', 'name', 'patronymic', 'site', 'skype', 'organization', 'phone', 'email'], 'trim'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'E-mail',
            'last_name' => 'Фамилия',
            'name' => 'Имя',
            'patronymic' => 'Отчество',
            'gender' => 'Пол',
            'birthday' => 'Дата рождения',
            'phone' => 'Телефон',
            'site' => 'Вэб-сайт',
            'skype' => 'Skype',
            'organization' => 'Организация',
            'geoId' => 'Регион',
        ];
    }

    public function geoName(): string
    {
        return $this->_user->geo_id ? $this->_user->geo->name
            : ($this->geoId ? Geo::find()->select('name')->where(['id' => $this->geoId])->scalar() : 'Выбрать регион');
    }
}