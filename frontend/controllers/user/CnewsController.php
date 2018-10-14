<?php

namespace frontend\controllers\user;


use core\access\Rbac;
use core\actions\CategorySelectAction;
use core\actions\DeletePhotoAction;
use core\actions\MovePhotoAction;
use core\actions\UploadAction;
use core\behaviors\ActiveUserBehavior;
use core\entities\CNews\CNews;
use core\entities\CNews\CNewsCategory;
use core\forms\CNews\CNewsForm;
use core\forms\manage\PhotosForm;
use core\readModels\CNewsReadRepository;
use core\repositories\CNews\CNewsRepository;
use core\useCases\CNewsService;
use core\useCases\manage\CNews\CNewsPhotoService;
use Yii;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class CnewsController extends Controller
{
    public $layout = '@frontend/views/user/cnews/layout';

    private $service;
    private $readRepository;
    private $_user;

    public function __construct(string $id, Module $module, CNewsService $service, CNewsReadRepository $readRepository, array $config = [])
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
                'entity' => CNewsCategory::class,
            ],
            'move-photo' => [
                'class' => MovePhotoAction::class,
                'entityClass' => CNews::class,
                'serviceClass' => CNewsPhotoService::class,
                'redirectUrl' => ['update', 'id' => '{id}', 'tab' => 'photos'],
            ],
            'delete-photo' => [
                'class' => DeletePhotoAction::class,
                'entityClass' => CNews::class,
                'serviceClass' => CNewsPhotoService::class,
                'redirectUrl' => ['update', 'id' => '{id}', 'tab' => 'photos'],
            ],
        ];
    }

    public function actionActive()
    {
        $this->selectedActionHandle();
        $provider = $this->readRepository->getCompanyActiveCNews($this->_user->company->id);
        Yii::$app->user->setReturnUrl(['/user/cnews/active']);

        return $this->render('active', [
            'provider' => $provider,
        ]);
    }

    public function actionWaiting()
    {
        $this->selectedActionHandle();
        $provider = $this->readRepository->getCompanyOnModerationCNews($this->_user->company->id);
        Yii::$app->user->setReturnUrl(['/user/cnews/waiting']);

        return $this->render('waiting', [
            'provider' => $provider,
        ]);
    }

    public function actionCreate()
    {
        $this->layout = 'main';
        $form = new CNewsForm();
        $form->scenario = CNewsForm::SCENARIO_USER_MANAGE;

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $article = $this->service->create($form);
                Yii::$app->session->setFlash('success', $article->isActive() ? "Новость опубликована" : "Новость отправлена на проверку");
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
        $news = (new CNewsRepository())->get($id);
        $form = new CNewsForm($news);
        $form->scenario = CNewsForm::SCENARIO_USER_MANAGE;

        if (!Yii::$app->user->can(Rbac::PERMISSION_MANAGE, ['user_id' => $news->user_id])) {
            throw new ForbiddenHttpException("У вас нет прав на это действие");
        }

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($news, $form);
                Yii::$app->session->setFlash('success', "Новость сохранена.");
                return $this->redirect([$news->isActive() ? 'active' : 'waiting']);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash("error", $e->getMessage());
            }
        }

        $photosForm = new PhotosForm();
        if ($photosForm->load(Yii::$app->request->post()) && $photosForm->validate()) {
            try {
                $this->service->addPhotos($news->id, $photosForm);
                Yii::$app->session->setFlash('success', 'Фотографии загружены');
                return $this->redirect(['update', 'id' => $news->id, 'tab' => 'photos']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'news' => $news,
            'tab' => Yii::$app->request->get('tab', 'main'),
        ]);
    }

    private function selectedActionHandle(): void
    {
        $ids = (array) Yii::$app->request->post('ids');
        $action = Yii::$app->request->post('action');
        if (count($ids)) {
            // Проверка на наличие прав на действие
            $cnewss = CNews::findAll(['id' => $ids]);
            foreach ($cnewss as $cnews) {
                if (!Yii::$app->user->can(Rbac::PERMISSION_MANAGE, ['user_id' => $cnews->user_id])) {
                    $key = array_search($cnews->id, $ids);
                    unset($ids[$key]);
                }
            }

            if ($action == 'remove') {
                $count = $this->service->massRemove($ids);
                Yii::$app->session->setFlash('info', 'Удалено новостей: ' . $count);
            }
        }
    }
}