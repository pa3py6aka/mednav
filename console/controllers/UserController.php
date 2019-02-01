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
        $this->stdout('User created, id: ' . $user->id . PHP_EOL, Console::FG_GREEN);
    }

    public function actionSetPassword()
    {
        $email = $this->prompt('E-mail:', ['required' => true]);
        $user = $this->findModel($email);
        $password = $this->prompt('Set new password:', ['required' => true, 'validator' => function($input, &$error) {
            if (\mb_strlen($input) < 6) {
                $error = 'Минимальная длина пароля 6 символов';
                return false;
            }
            return true;
        }]);
        $user->setPassword($password);
        if (!$user->save()) {
            throw new \DomainException('Ошибка записи в базу');
        }
        echo 'Пароль установлен' . PHP_EOL;
    }

    /**
     * Adds role to user
     */
    public function actionAssign(): void
    {
        $email = $this->prompt('Username:', ['required' => true]);
        $user = $this->findModel($email);
        $role = $this->select('Role:', ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description'));
        $this->service->assignRole($user->id, $role);
        $this->stdout('Done!' . PHP_EOL);
    }

    private function findModel($email): User
    {
        if (!$model = User::findOne(['email' => $email])) {
            throw new Exception('User is not found');
        }
        return $model;
    }


    /*public function actionUpdateLogos()
    {
        $path = Yii::getAlias('@frontend/web/i/company/lg/');
        $files = FileHelper::findFiles($path);
        $optimizerChain = OptimizerChainFactory::create();
        $sizes = [
            'small' => ['width' => Yii::$app->settings->get(Settings::COMPANY_SMALL_SIZE)],
            'big' => ['width' => Yii::$app->settings->get(Settings::COMPANY_BIG_SIZE)],
            'max' => ['width' => Yii::$app->settings->get(Settings::COMPANY_MAX_SIZE)],
        ];

        foreach ($files as $file) {
            if (strpos($file, 'gitignore')) {
                continue;
            }
            $dir = pathinfo($file, PATHINFO_DIRNAME);
            $name = pathinfo($file, PATHINFO_BASENAME);

            foreach ($sizes as $type => $item) {
                $width = isset($item['width']) && (int) $item['width'] ? (int) $item['width'] : null;
                $height = isset($item['height']) && (int) $item['height'] ? (int) $item['height'] : null;

                Image::resize($file, $width, $height)
                    ->save($dir . '/' . $type . '_' . $name);
                $optimizerChain->optimize($dir . '/' . $type . '_' . $name);
            }

            unlink($file);
        }
    }*/
}