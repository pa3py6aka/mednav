<?php

namespace frontend\controllers\user;


use core\access\Rbac;
use core\forms\Company\CompanyForm;
use core\forms\User\UpdatePasswordForm;
use core\forms\User\UserProfileForm;
use core\useCases\cabinet\ProfileService;
use Yii;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class AccountController extends Controller
{
    private $profile;
    private $_user;

    public function __construct(string $id, Module $module, ProfileService $profile, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->profile = $profile;
        $this->_user = Yii::$app->user->identity;
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
        return $this->render('index', ['user' => $this->_user]);
    }

    public function actionProfile($tab = 'main')
    {
        $profileForm = new UserProfileForm($this->_user);
        $accessForm = new UpdatePasswordForm($this->_user);

        if ($profileForm->load(Yii::$app->request->post()) && $profileForm->validate()) {
            try {
                $this->profile->editProfile($this->_user, $profileForm);
                Yii::$app->session->setFlash('success', 'Данные сохранены');
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        if ($type = Yii::$app->request->post('userType')) {
            try {
                $this->profile->editUserType($this->_user, $type);
                Yii::$app->session->setFlash('success', 'Тип профиля изменён');
                return $this->redirect(['profile', 'tab' => 'type']);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        if ($accessForm->load(Yii::$app->request->post()) && $accessForm->validate()) {
            try {
                $this->profile->updatePassword($this->_user, $accessForm->password);
                Yii::$app->session->setFlash('success', 'Новый пароль установлен');
                return $this->redirect(['profile', 'tab' => 'access']);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('profile', [
            'user' => $this->_user,
            'profileModel' => $profileForm,
            'passwordModel' => $accessForm,
            'tab' => $tab,
        ]);
    }

    public function actionCompany()
    {
        if (!$this->_user->isCompany()) {
            throw new ForbiddenHttpException('Ваш профиль не является компанией.');
        }

        $form = new CompanyForm($this->_user->company);

        return $this->render('company', [
            'user' => $this->_user,
            'model' => $form,
        ]);
    }
}