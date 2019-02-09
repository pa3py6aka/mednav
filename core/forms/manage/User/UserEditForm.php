<?php

namespace core\forms\manage\User;


use core\entities\User\User;
use Yii;
use yii\base\Model;

class UserEditForm extends Model
{
    public $type;
    public $email;
    public $password;
    public $role;
    public $lastName;
    public $name;
    public $patronymic;
    public $gender;
    public $birthday;
    public $phone;
    public $organization;
    public $site;
    public $skype;
    public $geoId;

    public $_user;

    public function __construct(User $user = null, array $config = [])
    {
        if ($user) {
            $this->_user = $user;
            $this->type = $user->type;
            $this->email = $user->email;
            $this->role = current(Yii::$app->authManager->getRolesByUser($user->id))->name;
            $this->lastName = $user->last_name;
            $this->name = $user->name;
            $this->patronymic = $user->patronymic;
            $this->gender = $user->gender;
            $this->birthday = Yii::$app->formatter->asDate($user->birthday, 'php:d.m.Y');
            $this->phone = $user->phone;
            $this->organization = $user->organization;
            $this->site = $user->site;
            $this->skype = $user->skype;
            $this->geoId = $user->geo_id;
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['type', 'email', 'role'], 'required'],
            ['type', 'in', 'range' => array_keys(User::getTypesArray())],
            ['password', 'string'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class, 'filter' => ['<>', 'id', $this->_user ? $this->_user->id : null]],

            ['geoId', 'required', 'when' => function ($model) {
                return $this->_user && !$this->_user->isCompany();
            }, 'whenClient' => "function (attribute, value) {
                return " . ($this->_user && $this->_user->isCompany() ? "null" : "1") . ";
            }"],
            ['geoId', 'integer'],

            [['lastName', 'name', 'patronymic', 'site', 'skype', 'organization', 'phone'], 'string', 'max' => 255],
            ['gender', 'in', 'range' => array_keys(User::gendersArray())],
            ['birthday', 'date', 'format' => 'php: d.m.Y'],

            [['lastName', 'name', 'patronymic', 'site', 'skype', 'organization', 'phone', 'email'], 'trim'],
            ['site', 'url', 'enableIDN' => true, 'defaultScheme' => 'http'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'type' => 'Тип',
            'email' => 'E-mail',
            'password' => 'Пароль',
            'role' => 'Роль',
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