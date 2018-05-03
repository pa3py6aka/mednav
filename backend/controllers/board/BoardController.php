<?php

namespace backend\controllers\board;

use core\actions\UploadAction;
use core\components\SettingsManager;
use core\entities\Board\BoardCategory;
use core\forms\manage\Board\BoardCreateForm;
use core\helpers\BoardHelper;
use core\useCases\manage\Board\BoardManageService;
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
     * Lists all Board models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BoardSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Board model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Board model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new BoardCreateForm();

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
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Board model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionGetChildren()
    {
        $id = (int) Yii::$app->request->post('id');
        $formName = Yii::$app->request->post('formName');
        $category = BoardCategory::findOne($id);
        return $this->asJson([
            'items' => $category->getChildren()->select(['id', 'name'])->asArray()->all(),
            'params' => BoardHelper::generateParameterFields($category, $formName),
        ]);
    }

    /**
     * Finds the Board model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Board the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Board::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
