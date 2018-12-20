<?php

namespace console\controllers;


use core\entities\User\User;
use yii\base\Module;
use yii\console\Controller;
use yii\db\Query;

class ImportController extends Controller
{
    private $command;

    public function __construct(string $id, Module $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->command = (new Query())->createCommand(\Yii::$app->get('oldDb'));
    }

    public function actionUsers()
    {
        echo 'Starting users import...' . PHP_EOL;
        User::deleteAll();

        $sql = 'SELECT `as`.`item_name`,`u`.* FROM `users` `u` LEFT JOIN `auth_assignment` `as` on `as`.`user_id`=`u`.`id` ORDER BY `u`.`id`';
        $query = $this->command->setSql($sql);
        foreach ($query->queryAll() as $oldUser) {
            $user = new User([
                'email' => $oldUser['email'],
                'type' => $oldUser['item_name'] === 'company' ? User::TYPE_COMPANY : User::TYPE_USER,
                'geo_id' => null,
                'last_name' => '',
                'name' => '',
                'patronymic' => '',
                'gender' => User::GENDER_NOT_SET,
                'birthday' => null,
                'phone' => '',
                'site' => '',
                'skype' => '',
                'organization' => '',
                'last_online' => 0,
                'status' => $oldUser['is_active'] == '1' ? User::STATUS_ACTIVE : User::STATUS_DELETED,
                'auth_key' => $oldUser['auth_key'],
                'password_hash' => $oldUser['password'],
                'password_reset_token' => null,
                'email_confirm_token' => $oldUser['confirm_hash'],
                'created_at' => strtotime($oldUser['created_at']),
                'updated_at' => strtotime($oldUser['created_at']),
            ]);
            echo $oldUser['email'] . ' : ' . strtotime($oldUser['created_at']) . ' : ' . \Yii::$app->formatter->asDatetime(strtotime($oldUser['created_at']));

            echo PHP_EOL;
            break;
        }
    }
}