<?php

namespace frontend\controllers\user;


use core\access\Rbac;
use core\forms\User\UpdatePasswordForm;
use core\forms\User\UserProfileForm;
use core\useCases\cabinet\ProfileService;
use Yii;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\web\Controller;

class AccountController extends Controller
{
    private $profile;

    public function __construct(string $id, Module $module, ProfileService $profile, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->profile = $profile;
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [Rbac::ROLE_USER],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index', ['user' => Yii::$app->user->identity]);
    }

    public function actionProfile($tab = 'main')
    {
        $user = Yii::$app->user->identity;
        $profileForm = new UserProfileForm($user);
        $accessForm = new UpdatePasswordForm($user);

        if ($profileForm->load(Yii::$app->request->post()) && $profileForm->validate()) {
            try {
                $this->profile->editProfile($user, $profileForm);
                Yii::$app->session->setFlash('success', 'Данные сохранены');
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        if ($type = Yii::$app->request->post('userType')) {
            try {
                $this->profile->editUserType($user, $type);
                Yii::$app->session->setFlash('success', 'Тип профиля изменён');
                return $this->redirect(['profile', 'tab' => 'type']);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        if ($accessForm->load(Yii::$app->request->post()) && $accessForm->validate()) {
            try {
                $this->profile->updatePassword($user, $accessForm->password);
                Yii::$app->session->setFlash('success', 'Новый пароль установлен');
                return $this->redirect(['profile', 'tab' => 'access']);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('profile', [
            'user' => $user,
            'profileModel' => $profileForm,
            'passwordModel' => $accessForm,
            'tab' => $tab,
        ]);
    }
}