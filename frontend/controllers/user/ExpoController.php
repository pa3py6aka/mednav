<?php

namespace frontend\controllers\user;


use core\access\Rbac;
use core\actions\CategorySelectAction;
use core\actions\DeletePhotoAction;
use core\actions\MovePhotoAction;
use core\actions\UploadAction;
use core\behaviors\ActiveUserBehavior;
use core\entities\Expo\Expo;
use core\entities\Expo\ExpoCategory;
use core\forms\Expo\ExpoForm;
use core\forms\manage\PhotosForm;
use core\readModels\ExpoReadRepository;
use core\repositories\Expo\ExpoRepository;
use core\useCases\ExpoService;
use core\useCases\manage\Expo\ExpoPhotoService;
use Yii;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class ExpoController extends Controller
{
    public $layout = '@frontend/views/user/expo/layout';

    private $service;
    private $readRepository;
    private $_user;

    public function __construct(string $id, Module $module, ExpoService $service, ExpoReadRepository $readRepository, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->readRepository = $readRepository;
        $this->_user = Yii::$app->user->identity;
        $this->view->params['user'] = $this->_user;
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
            'activeUser' => [
                'class' => ActiveUserBehavior::class,
                'onlyForCompany' => true,
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ]
            ]
        ];
    }

    public function actions()
    {
        return [
            'upload' => [
                'class' => UploadAction::class,
            ],
            'select-category' => [
                'class' => CategorySelectAction::class,
                'entity' => ExpoCategory::class,
            ],
            'move-photo' => [
                'class' => MovePhotoAction::class,
                'entityClass' => Expo::class,
                'serviceClass' => ExpoPhotoService::class,
                'redirectUrl' => ['update', 'id' => '{id}', 'tab' => 'photos'],
            ],
            'delete-photo' => [
                'class' => DeletePhotoAction::class,
                'entityClass' => Expo::class,
                'serviceClass' => ExpoPhotoService::class,
                'redirectUrl' => ['update', 'id' => '{id}', 'tab' => 'photos'],
            ],
        ];
    }

    public function actionActive()
    {
        $this->selectedActionHandle();
        $provider = $this->readRepository->getCompanyActiveExpos($this->_user->company->id);
        Yii::$app->user->setReturnUrl(['/user/expo/active']);

        return $this->render('active', [
            'provider' => $provider,
        ]);
    }

    public function actionWaiting()
    {
        $this->selectedActionHandle();
        $provider = $this->readRepository->getCompanyOnModerationExpos($this->_user->company->id);
        Yii::$app->user->setReturnUrl(['/user/expo/waiting']);

        return $this->render('waiting', [
            'provider' => $provider,
        ]);
    }

    public function actionCreate()
    {
        $this->layout = 'main';
        $form = new ExpoForm();
        $form->scenario = ExpoForm::SCENARIO_USER_MANAGE;

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $article = $this->service->create($form);
                Yii::$app->session->setFlash('success', $article->isActive() ? "Выставка опубликована" : "Выставка отправлена на проверку");
                return $this->redirect([$article->isActive() ? 'active' : 'waiting']);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash("error", $e->getMessage());
            }
        }

        return $this->render('create', ['model' => $form]);
    }

    public function actionUpdate($id)
    {
        $this->layout = 'main';
        $expo = (new ExpoRepository())->get($id);
        $form = new ExpoForm($expo);
        $form->scenario = ExpoForm::SCENARIO_USER_MANAGE;

        if (!Yii::$app->user->can(Rbac::PERMISSION_MANAGE, ['user_id' => $expo->user_id])) {
            throw new ForbiddenHttpException("У вас нет прав на это действие");
        }

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($id, $form);
                Yii::$app->session->setFlash('success', "Выставка сохранена.");
                return $this->redirect([$expo->isActive() ? 'active' : 'waiting']);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash("error", $e->getMessage());
            }
        }

        $photosForm = new PhotosForm();
        if ($photosForm->load(Yii::$app->request->post()) && $photosForm->validate()) {
            try {
                $this->service->addPhotos($expo->id, $photosForm);
                Yii::$app->session->setFlash('success', 'Фотографии загружены');
                return $this->redirect(['update', 'id' => $expo->id, 'tab' => 'photos']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'expo' => $expo,
            'tab' => Yii::$app->request->get('tab', 'main'),
        ]);
    }

    private function selectedActionHandle(): void
    {
        $ids = (array) Yii::$app->request->post('ids');
        $action = Yii::$app->request->post('action');
        if (count($ids)) {
            // Проверка на наличие прав на действие
            $expos = Expo::findAll(['id' => $ids]);
            foreach ($expos as $expo) {
                if (!Yii::$app->user->can(Rbac::PERMISSION_MANAGE, ['user_id' => $expo->user_id])) {
                    $key = array_search($expo->id, $ids);
                    unset($ids[$key]);
                }
            }

            if ($action == 'remove') {
                $count = $this->service->massRemove($ids);
                Yii::$app->session->setFlash('info', 'Удалено выставок: ' . $count);
            }
        }
    }
}