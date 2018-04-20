<?php

namespace console\controllers;


use core\entities\User\User;
use core\forms\manage\User\UserCreateForm;
use core\useCases\manage\UserManageService;
use Yii;
use yii\console\Controller;
use yii\console\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;

class UserController extends Controller
{
    private $service;

    public function __construct($id, $module, UserManageService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    /**
     * Create new user
     */
    public function actionCreate()
    {
        $email = $this->prompt('E-mail:', ['required' => true]);
        $password = $this->prompt('Password:', ['required' => true]);
        $type = $this->confirm('Is this is a company?') ? User::TYPE_COMPANY : User::TYPE_USER;
        $role = $this->select('Role:', ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description'));

        $form = new UserCreateForm([
            'type' => $type,
            'email' => $email,
            'password' => $password,
            'role' => $role,
        ]);
        if (!$form->validate()) {
            $this->stderr(implode(PHP_EOL, $form->getFirstErrors()) . PHP_EOL, Console::FG_RED);
            Yii::$app->end();
        }

        $user = $this->service->create($form);
        $this->stdout("User created, id: " . $user->id . PHP_EOL, Console::FG_GREEN);
    }

    /**
     * Adds role to user
     */
    public function actionAssign(): void
    {
        $username = $this->prompt('Username:', ['required' => true]);
        $user = $this->findModel($username);
        $role = $this->select('Role:', ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description'));
        $this->service->assignRole($user->id, $role);
        $this->stdout('Done!' . PHP_EOL);
    }

    private function findModel($username): User
    {
        if (!$model = User::findOne(['email' => $username])) {
            throw new Exception('User is not found');
        }
        return $model;
    }
}