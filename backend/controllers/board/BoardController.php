<?php

namespace backend\controllers\board;

use core\actions\UploadAction;
use core\components\SettingsManager;
use core\entities\Board\BoardCategory;
use core\forms\manage\Board\BoardManageForm;
use core\forms\manage\Board\BoardPhotosForm;
use core\helpers\BoardHelper;
use core\useCases\manage\Board\BoardManageService;
use core\useCases\manage\Board\BoardPhotoService;
use Yii;
use core\entities\Board\Board;
use backend\forms\BoardSearch;
use yii\base\Module;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BoardController implements the CRUD actions for Board model.
 */
class BoardController extends Controller
{
    private $service;

    public function __construct($id, Module $module, BoardManageService $service, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'delete-photo' => ['POST'],
                    'move-photo' => ['POST'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'upload' => [
                'class' => UploadAction::class,
                'baseUrl' => Yii::$app->params['frontendHostInfo'] . '/tmp',
                'sizes' => [
                    'small' => ['width' => Yii::$app->settings->get(SettingsManager::BOARD_SMALL_SIZE)],
                    'big' => ['width' => Yii::$app->settings->get(SettingsManager::BOARD_BIG_SIZE)],
                    'max' => ['width' => Yii::$app->settings->get(SettingsManager::BOARD_MAX_SIZE)],
                ]
            ],
        ];
    }

    /**
     * Листинг размещённых объявлений.
     * @return mixed
     */
    public function actionActive()
    {
        $this->selectedActionHandle();

        $searchModel = new BoardSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('active', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Листинг объявлений в архиве.
     * @return mixed
     */
    public function actionArchive()
    {
        $this->selectedActionHandle();

        $searchModel = new BoardSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'archive');

        return $this->render('archive', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Листинг объявлений на проверку.
     * @return mixed
     */
    public function actionModeration()
    {
        $this->selectedActionHandle();

        $searchModel = new BoardSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'moderation');

        return $this->render('moderation', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Листинг удалённых объявлений.
     * @return mixed
     */
    public function actionDeleted()
    {
        $this->selectedActionHandle(true);

        $searchModel = new BoardSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'deleted');

        return $this->render('deleted', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Board model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id, $tab = 'main')
    {
        $board = $this->findModel($id);

        $photosForm = new BoardPhotosForm();
        if ($photosForm->load(Yii::$app->request->post()) && $photosForm->validate()) {
            try {
                $this->service->addPhotos($board->id, $photosForm);
                Yii::$app->session->setFlash('success', 'Фотографии загружены');
                return $this->redirect(['view', 'id' => $board->id, 'tab' => 'photos']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('view', [
            'model' => $board,
            'photosForm' => $photosForm,
            'tab' => $tab,
        ]);
    }

    /**
     * Creates a new Board model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new BoardManageForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $board = $this->service->create($form);
                return $this->redirect(['view', 'id' => $board->id]);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $form,
        ]);
    }

    /**
     * Updates an existing Board model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $board = $this->findModel($id);
        $form = new BoardManageForm($board);
        $form->scenario = BoardManageForm::SCENARIO_ADMIN_EDIT;

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $this->service->edit($id, $form);
            Yii::$app->session->setFlash('success', 'Объявление обновлено');
            return $this->redirect(['view', 'id' => $board->id]);
        }

        return $this->render('update', [
            'model' => $form,
            'board' => $board,
        ]);
    }

    public function actionDelete($id, $hard = 0)
    {
        try {
            $this->service->remove($id, !(bool) $hard);
            Yii::$app->session->setFlash('success', 'Объявление удалено' . ($hard ? ' полностью из базы' : ''));
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionMovePhoto($board_id, $photo_id, $direction)
    {
        $board = $this->findModel($board_id);
        try {
            Yii::createObject(BoardPhotoService::class)->movePhoto($board, $photo_id, $direction);
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['view', 'id' => $board->id, 'tab' => 'photos']);
    }

    public function actionDeletePhoto($id, $photo_id)
    {
        $board = $this->findModel($id);
        try {
            Yii::createObject(BoardPhotoService::class)->removePhoto($board, $photo_id);
            Yii::$app->session->setFlash('info', 'Фотография удалена');
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['view', 'id' => $board->id, 'tab' => 'photos']);
    }

    public function actionGetChildren()
    {
        $id = (int) Yii::$app->request->post('id');
        $formName = Yii::$app->request->post('formName');
        $category = BoardCategory::findOne($id);
        return $this->asJson([
            'items' => $category->getChildren()->active()->select(['id', 'name'])->asArray()->all(),
            'params' => BoardHelper::generateParameterFields($category, $formName),
        ]);
    }

    /**
     * Отслеживание нажатия кнопок действий с выбранными элементами (Удалить выбранные,продлить и так далее)
     * @param bool $hardRemove флаг удалять сообщения полностью из базы или нет
     */
    private function selectedActionHandle($hardRemove = false): void
    {
        $ids = (array) Yii::$app->request->post('ids');
        $action = Yii::$app->request->post('action');
        if (count($ids)) {
            if ($action == 'remove') {
                $count = $this->service->massRemove($ids, $hardRemove);
                Yii::$app->session->setFlash('info', 'Удалено объявлений: ' . $count);
            } else if ($action == 'extend') {
                $term = Yii::$app->request->post('term');
                $this->service->extend($ids, $term);
                Yii::$app->session->setFlash('info', 'Продлено объявлений: ' . count($ids));
            } else if ($action == 'publish') {
                $this->service->publish($ids);
                Yii::$app->session->setFlash('info', 'Опубликовано объявлений: ' . count($ids));
            }
        }
    }

    /**
     * Finds the Board model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Board the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    private function findModel($id)
    {
        if (($model = Board::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
