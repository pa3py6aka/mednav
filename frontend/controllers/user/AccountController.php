<?php

namespace frontend\controllers\user;


use core\access\Rbac;
use core\actions\UploadAction;
use core\components\SettingsManager;
use core\forms\Company\CompanyForm;
use core\forms\User\UpdatePasswordForm;
use core\forms\User\UserProfileForm;
use core\useCases\cabinet\ProfileService;
use core\useCases\CompanyService;
use Yii;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class AccountController extends Controller
{
    private $profile;
    private $companyService;
    private $_user;

    public function __construct(string $id, Module $module, ProfileService $profile, CompanyService $companyService, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->profile = $profile;
        $this->companyService = $companyService;
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

    public function actions()
    {
        return [
            'company-photo-upload' => [
                'class' => UploadAction::class,
                'sizes' => [
                    'small' => ['width' => Yii::$app->settings->get(SettingsManager::COMPANY_SMALL_SIZE)],
                    'big' => ['width' => Yii::$app->settings->get(SettingsManager::COMPANY_BIG_SIZE)],
                    'max' => ['width' => Yii::$app->settings->get(SettingsManager::COMPANY_MAX_SIZE)],
                ],
            ],
            /*'move-photo' => [
                'class' => MovePhotoAction::class,
                'entityClass' => Board::class,
                'redirectUrl' => ['update', 'id' => '{id}', 'tab' => 'photos'],
            ],
            'delete-photo' => [
                'class' => DeletePhotoAction::class,
                'entityClass' => Board::class,
                'redirectUrl' => ['update', 'id' => '{id}', 'tab' => 'photos'],
            ],*/
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
        $form->scenario = CompanyForm::SCENARIO_USER_MANAGE;

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $dText = "";
                if ($this->_user->company) {
                    $this->companyService->edit($form);
                } else {
                    $company = $this->companyService->create($form);
                    $dText = $company->isOnModeration() ? "<br>Ваша компания отправлена на проверку модераторам." : "";
                }
                Yii::$app->session->setFlash("success", "Данные компании сохранены." . $dText);
                return $this->redirect(['company']);
            } catch (\DomainException $e) {
                Yii::$app->session->set('error', $e->getMessage());
            }
        }

        return $this->render('company', [
            'user' => $this->_user,
            'model' => $form,
        ]);
    }
}