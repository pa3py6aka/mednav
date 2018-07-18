<?php

namespace frontend\controllers\user;


use core\access\Rbac;
use core\actions\CategorySelectAction;
use core\actions\DeletePhotoAction;
use core\actions\MovePhotoAction;
use core\actions\UploadAction;
use core\behaviors\ActiveUserBehavior;
use core\entities\Trade\Trade;
use core\entities\Trade\TradeCategory;
use core\forms\manage\PhotosForm;
use core\forms\manage\Trade\TradeManageForm;
use core\forms\manage\Trade\TradeUserCategoryForm;
use core\readModels\Trade\TradeReadRepository;
use core\repositories\Trade\TradeRepository;
use core\useCases\manage\Trade\TradeManageService;
use core\useCases\manage\Trade\TradePhotoService;
use Yii;
use yii\base\Module;
use yii\base\UserException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class TradeController extends Controller
{
    public $layout = '@frontend/views/user/trade/layout';

    private $service;
    private $_user;

    public function __construct(string $id, Module $module, TradeManageService $service, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
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
                'entity' => TradeCategory::class,
            ],
            'move-photo' => [
                'class' => MovePhotoAction::class,
                'entityClass' => Trade::class,
                'serviceClass' => TradePhotoService::class,
                'redirectUrl' => ['update', 'id' => '{id}', 'tab' => 'photos'],
            ],
            'delete-photo' => [
                'class' => DeletePhotoAction::class,
                'entityClass' => Trade::class,
                'serviceClass' => TradePhotoService::class,
                'redirectUrl' => ['update', 'id' => '{id}', 'tab' => 'photos'],
            ],
        ];
    }

    public function actionActive()
    {
        $this->selectedActionHandle();
        $repository = new TradeReadRepository();
        $provider = $repository->getUserTrades($this->_user->id, Trade::STATUS_ACTIVE);
        Yii::$app->user->setReturnUrl(['/user/trade/active']);

        return $this->render('active', [
            'provider' => $provider,
        ]);
    }

    public function actionWaiting()
    {
        $this->selectedActionHandle();
        $repository = new TradeReadRepository();
        $provider = $repository->getUserTrades($this->_user->id, Trade::STATUS_ON_PREMODERATION);
        Yii::$app->user->setReturnUrl(['/user/trade/waiting']);

        return $this->render('waiting', [
            'provider' => $provider,
        ]);
    }

    public function actionCreate()
    {
        if (!(new TradeReadRepository())->getUserCategoriesCount($this->_user->id)) {
            throw new UserException(
                "Для добавления товаров необходимо добавить хотя бы одну пользовательскую категорию.<br><br>" .
                "<a href=\"" .Url::to(['category-create']). "\" class='btn btn-primary'>Добавить категорию.</a>"
            );
        }

        $this->layout = 'main';
        $form = new TradeManageForm();
        $form->scenario = TradeManageForm::SCENARIO_USER_CREATE;

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $trade = $this->service->create($form);
                Yii::$app->session->setFlash('success', $trade->isActive() ? "Товар опубликован" : "Товар отправлен на проверку");
                return $this->redirect([$trade->isActive() ? 'active' : 'waiting']);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash("error", $e->getMessage());
            }
        }

        return $this->render('create', ['model' => $form]);
    }

    public function actionUpdate($id)
    {
        $this->layout = 'main';
        $trade = (new TradeRepository())->get($id);
        $form = new TradeManageForm($trade);
        $form->scenario = TradeManageForm::SCENARIO_USER_EDIT;

        if (!Yii::$app->user->can(Rbac::PERMISSION_MANAGE, ['user_id' => $trade->user_id])) {
            throw new ForbiddenHttpException("У вас нет прав на это действие");
        }

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($trade, $form);
                Yii::$app->session->setFlash('success', "Данные по товару обновлены.");
                return $this->redirect([$trade->isActive() ? 'active' : 'waiting']);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash("error", $e->getMessage());
            }
        }

        $photosForm = new PhotosForm();
        if ($photosForm->load(Yii::$app->request->post()) && $photosForm->validate()) {
            try {
                $this->service->addPhotos($trade->id, $photosForm);
                Yii::$app->session->setFlash('success', 'Фотографии загружены');
                return $this->redirect(['update', 'id' => $trade->id, 'tab' => 'photos']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'trade' => $trade,
            'tab' => Yii::$app->request->get('tab', 'main'),
        ]);
    }

    public function actionCategoryCreate()
    {
        $this->layout = 'main';
        $form = new TradeUserCategoryForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->createUserCategory($this->_user->id, $form);
                Yii::$app->session->setFlash("success", "Категория успешно добавлена.");
                return $this->redirect(['categories']);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash("error", $e->getMessage());
            }
        }

        return $this->render('category-create', [
            'model' => $form,
        ]);
    }

    public function actionCategoryUpdate($id)
    {
        $this->layout = 'main';
        $userCategory = (new TradeRepository())->getUserCategory($id);
        $form = new TradeUserCategoryForm($userCategory);

        if (!Yii::$app->user->can(Rbac::PERMISSION_MANAGE, ['user_id' => $userCategory->user_id])) {
            throw new ForbiddenHttpException("Вы не имеете прав на редактирование данной категории.");
        }

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->editUserCategory($userCategory, $form);
                Yii::$app->session->setFlash("success", "Категория сохранена.");
                return $this->redirect(['categories']);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash("error", $e->getMessage());
            }
        }

        return $this->render('category-update', [
            'model' => $form,
        ]);
    }

    public function actionCategoryRemove($id)
    {
        $tradeRepository = new TradeRepository();
        $userCategory = $tradeRepository->getUserCategory($id);

        if (!Yii::$app->user->can(Rbac::PERMISSION_MANAGE, ['user_id' => $userCategory->user_id])) {
            throw new ForbiddenHttpException("Вы не имеете прав на удаение данной категории.");
        }

        try {
            $tradeRepository->removeUserCategory($userCategory);
            Yii::$app->session->setFlash("success", "Категория удлена вместе с привязанными к ней товарами.");
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash("error", $e->getMessage());
        }

        return $this->redirect(['categories']);
    }

    public function actionCategory($id)
    {
        $this->layout = 'main';
        $userCategory = (new TradeRepository())->getUserCategory($id);

        if (!Yii::$app->user->can(Rbac::PERMISSION_MANAGE, ['user_id' => $userCategory->user_id])) {
            throw new ForbiddenHttpException("Вы не имеете прав на просмотр данной категории.");
        }

        return $this->render('category', [
            'category' => $userCategory,
        ]);
    }

    public function actionCategories()
    {
        $provider = (new TradeReadRepository())->getUserCategories($this->_user->id);

        return $this->render('categories', [
            'provider' => $provider,
        ]);
    }

    private function selectedActionHandle(): void
    {
        $ids = (array) Yii::$app->request->post('ids');
        $action = Yii::$app->request->post('action');
        if (count($ids)) {
            // Проверка на наличие прав на действие
            $trades = Trade::findAll(['id' => $ids]);
            foreach ($trades as $trade) {
                if (!Yii::$app->user->can(Rbac::PERMISSION_MANAGE, ['user_id' => $trade->user_id])) {
                    $key = array_search($trade->id, $ids);
                    unset($ids[$key]);
                }
            }

            if ($action == 'remove') {
                $count = $this->service->massRemove($ids);
                Yii::$app->session->setFlash('info', 'Удалено товаров: ' . $count);
            }
        }
    }
}