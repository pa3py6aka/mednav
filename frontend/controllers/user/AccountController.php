<?php

namespace frontend\controllers\user;


use core\access\Rbac;
use core\actions\DeletePhotoAction;
use core\actions\MovePhotoAction;
use core\actions\UploadAction;
use core\components\SettingsManager;
use core\entities\Company\Company;
use core\forms\Company\CompanyForm;
use core\forms\manage\PhotosForm;
use core\forms\User\UpdatePasswordForm;
use core\forms\User\UserProfileForm;
use core\useCases\cabinet\ProfileService;
use core\useCases\CompanyService;
use core\useCases\manage\Company\CompanyPhotoService;
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
            'move-photo' => [
                'class' => MovePhotoAction::class,
                'entityClass' => Company::class,
                'serviceClass' => CompanyPhotoService::class,
                'redirectUrl' => ['company', 'tab' => 'photos'],
            ],
            'delete-photo' => [
                'class' => DeletePhotoAction::class,
                'entityClass' => Company::class,
                'serviceClass' => CompanyPhotoService::class,
                'redirectUrl' => ['company', 'tab' => 'photos'],
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

        $tab =Yii::$app->request->get('tab', 'main');

        $form = new CompanyForm($this->_user->company);
        $form->scenario = CompanyForm::SCENARIO_USER_MANAGE;

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $dText = "";
                if ($this->_user->company) {
                    $this->companyService->edit($this->_user->company->id, $form);
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

        if ($this->_user->company) {
            $photosForm = new PhotosForm();
            if ($photosForm->load(Yii::$app->request->post()) && $photosForm->validate()) {
                try {
                    $this->companyService->addPhotos($this->_user->company->id, $photosForm);
                    Yii::$app->session->setFlash('success', 'Фотографии загружены');
                    return $this->redirect(['company', 'tab' => 'photos']);
                } catch (\DomainException $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('error', $e->getMessage());
                    $tab = 'photos';
                }
            }
        }

        return $this->render('company', [
            'user' => $this->_user,
            'model' => $form,
            'tab' => $tab,
        ]);
    }
}