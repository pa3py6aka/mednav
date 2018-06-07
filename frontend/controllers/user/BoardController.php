<?php

namespace frontend\controllers\user;


use core\access\Rbac;
use core\actions\BoardCategorySelectAction;
use core\actions\DeletePhotoAction;
use core\actions\MovePhotoAction;
use core\actions\UploadAction;
use core\components\SettingsManager;
use core\entities\Board\Board;
use core\forms\manage\Board\BoardManageForm;
use core\forms\manage\Board\BoardPhotosForm;
use core\readModels\Board\BoardReadRepository;
use core\repositories\Board\BoardRepository;
use core\useCases\manage\Board\BoardManageService;
use Yii;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class BoardController extends Controller
{
    public $layout = '@frontend/views/user/board/layout';

    private $service;
    private $_user;

    public function __construct(string $id, Module $module, BoardManageService $service, array $config = [])
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
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                    'move-photo' => ['post'],
                    'delete-photo' => ['post'],
                    'upload' => ['post'],
                    'select-category' => ['post'],
                    'extend' => ['post'],
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
                'class' => BoardCategorySelectAction::class,
            ],
            'move-photo' => [
                'class' => MovePhotoAction::class,
                'entityClass' => Board::class,
                'redirectUrl' => ['update', 'id' => '{id}', 'tab' => 'photos'],
            ],
            'delete-photo' => [
                'class' => DeletePhotoAction::class,
                'entityClass' => Board::class,
                'redirectUrl' => ['update', 'id' => '{id}', 'tab' => 'photos'],
            ],
        ];
    }

    public function actionActive()
    {
        $this->selectedActionHandle();
        $repository = new BoardReadRepository();
        $provider = $repository->getUserBoards($this->_user->id, Board::STATUS_ACTIVE);
        Yii::$app->user->setReturnUrl(['/user/board/active']);
        $toExtend = $repository->toExtendCount($this->_user->id);

        $provider->getSort()->attributes['active_until'] = [
            'asc' => ['b.active_until' => SORT_ASC],
            'desc' => ['b.active_until' => SORT_DESC],
        ];
        $provider->getSort()->attributes['views'] = [
            'asc' => ['b.views' => SORT_ASC],
            'desc' => ['b.views' => SORT_DESC],
        ];

        return $this->render('active', [
            'provider' => $provider,
            'toExtend' => $toExtend,
        ]);
    }

    public function actionArchive()
    {
        $this->selectedActionHandle();
        $provider = (new BoardReadRepository())->getUserBoards($this->_user->id, Board::STATUS_ARCHIVE);
        Yii::$app->user->setReturnUrl(['/user/board/archive']);

        $provider->getSort()->attributes['views'] = [
            'asc' => ['b.views' => SORT_ASC],
            'desc' => ['b.views' => SORT_DESC],
        ];

        return $this->render('archive', [
            'provider' => $provider,
        ]);
    }

    public function actionWaiting()
    {
        $provider = (new BoardReadRepository())->getUserBoards($this->_user->id, Board::STATUS_ON_MODERATION);
        Yii::$app->user->setReturnUrl(['/user/board/waiting']);

        return $this->render('waiting', [
            'provider' => $provider,
        ]);
    }

    public function actionCreate()
    {
        $this->layout = 'main';
        $form = new BoardManageForm();
        $form->scenario = BoardManageForm::SCENARIO_USER_CREATE;

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $board = $this->service->create($form);
                Yii::$app->session->setFlash('success', $board->isActive() ? "Объявление опубликовано" : "Объявление отправлено на проверку");
                return $this->redirect([$board->isActive() ? 'active' : 'waiting']);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash("error", $e->getMessage());
            }
        }

        return $this->render('create', ['model' => $form]);
    }

    public function actionUpdate($id)
    {
        $this->layout = 'main';
        $board = (new BoardRepository())->get($id);

        if (!Yii::$app->user->can(Rbac::PERMISSION_MANAGE, ['user_id' => $board->author_id])) {
            throw new ForbiddenHttpException("У вас нет прав на это действие");
        }

        $form = new BoardManageForm($board);
        $form->scenario = BoardManageForm::SCENARIO_USER_EDIT;
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $this->service->edit($id, $form);
            Yii::$app->session->setFlash('success', 'Объявление обновлено');
            return $this->redirect(['update', 'id' => $board->id]);
        }

        $photosForm = new BoardPhotosForm();
        if ($photosForm->load(Yii::$app->request->post()) && $photosForm->validate()) {
            try {
                $this->service->addPhotos($board->id, $photosForm);
                Yii::$app->session->setFlash('success', 'Фотографии загружены');
                return $this->redirect(['update', 'id' => $board->id, 'tab' => 'photos']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'board' => $board,
            'photosForm' => $photosForm,
            'tab' => Yii::$app->request->get('tab', 'main'),
        ]);
    }

    public function actionDelete($id)
    {
        try {
            $this->service->remove($id);
            Yii::$app->session->setFlash('success', 'Объявление удалено');
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->goBack(['/user/board/active']);
    }

    public function actionExtend()
    {
        if (Yii::$app->request->get('all')) {
            $ids = (new BoardReadRepository())->toExtendIds($this->_user->id);
        } else {
            $ids = (array) Yii::$app->request->post('ids');
            $boards = Board::findAll(['id' => $ids]);
            foreach ($boards as $board) {
                if (!$board->canExtend($this->_user->id)) {
                    $key = array_search($board->id, $ids);
                    unset($ids[$key]);
                }
            }
        }
        if ($ids) {
            $this->service->extend($ids, Yii::$app->request->post('term'));
        }
        Yii::$app->session->setFlash('info', 'Продлено объявлений: ' . count($ids));

        return $this->goBack(['/user/board/active']);
    }

    private function selectedActionHandle(): void
    {
        $ids = (array) Yii::$app->request->post('ids');
        $action = Yii::$app->request->post('action');
        if (count($ids)) {
            // Проверка на наличие прав на действие
            $boards = Board::findAll(['id' => $ids]);
            foreach ($boards as $board) {
                if (!$board->canExtend($this->_user->id)) {
                    $key = array_search($board->id, $ids);
                    unset($ids[$key]);
                }
            }

            if ($action == 'remove') {
                $count = $this->service->massRemove($ids);
                Yii::$app->session->setFlash('info', 'Удалено объявлений: ' . $count);
            } else if ($action == 'extend') {
                $term = Yii::$app->request->post('term');
                $this->service->extend($ids, $term);
                Yii::$app->session->setFlash('info', 'Продлено объявлений: ' . count($ids));
            }/* else if ($action == 'publish') {
                $this->service->publish($ids);
                Yii::$app->session->setFlash('info', 'Опубликовано объявлений: ' . count($ids));
            }*/
        }
    }
}