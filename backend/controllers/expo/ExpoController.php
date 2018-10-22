<?php

namespace backend\controllers\expo;


use backend\forms\ExpoSearch;
use core\actions\CategorySelectAction;
use core\actions\DeletePhotoAction;
use core\actions\MovePhotoAction;
use core\actions\UploadAction;
use core\entities\Expo\Expo;
use core\entities\Expo\ExpoCategory;
use core\forms\Expo\ExpoForm;
use core\forms\manage\PhotosForm;
use core\repositories\Expo\ExpoRepository;
use core\useCases\ExpoService;
use core\useCases\manage\Expo\ExpoPhotoService;
use Yii;
use yii\base\Module;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ExpoController extends Controller
{
    private $service;
    private $repository;

    public function __construct(string $id, Module $module, ExpoService $service, ExpoRepository $repository, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->repository = $repository;
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
            'select-category' => [
                'class' => CategorySelectAction::class,
                'entity' => ExpoCategory::class,
            ],
            'upload' => [
                'class' => UploadAction::class,
            ],
            'move-photo' => [
                'class' => MovePhotoAction::class,
                'entityClass' => Expo::class,
                'serviceClass' => ExpoPhotoService::class,
            ],
            'delete-photo' => [
                'class' => DeletePhotoAction::class,
                'entityClass' => Expo::class,
                'serviceClass' => ExpoPhotoService::class,
            ],
        ];
    }

    /**
     * Листинг размещённых брендов.
     * @return mixed
     */
    public function actionActive()
    {
        $this->selectedActionHandle();

        $searchModel = new ExpoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('active', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Листинг брендов на проверку.
     * @return mixed
     */
    public function actionModeration()
    {
        $this->selectedActionHandle();

        $searchModel = new ExpoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'moderation');

        return $this->render('moderation', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Листинг удалённых брендов.
     * @return mixed
     */
    public function actionDeleted()
    {
        $this->selectedActionHandle(true);

        $searchModel = new ExpoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'deleted');

        return $this->render('deleted', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id, $tab = 'main')
    {
        $brand = $this->findModel($id);

        $photosForm = new PhotosForm();
        if ($photosForm->load(Yii::$app->request->post()) && $photosForm->validate()) {
            try {
                $this->service->addPhotos($brand->id, $photosForm);
                Yii::$app->session->setFlash('success', 'Фотографии загружены');
                return $this->redirect(['view', 'id' => $brand->id, 'tab' => 'photos']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('view', [
            'model' => $brand,
            'photosForm' => $photosForm,
            'tab' => $tab,
        ]);
    }

    public function actionCreate()
    {
        $form = new ExpoForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $brand = $this->service->create($form);
                return $this->redirect(['view', 'id' => $brand->id]);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $form,
        ]);
    }

    public function actionUpdate($id)
    {
        $brand = $this->findModel($id);
        $form = new ExpoForm($brand);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $this->service->edit($id, $form);
            Yii::$app->session->setFlash('success', 'Выставка обновлена.');
            return $this->redirect(['view', 'id' => $brand->id]);
        }

        return $this->render('update', [
            'model' => $form,
        ]);
    }

    public function actionDelete($id, $hard = 0)
    {
        try {
            $this->service->remove($id, !(bool) $hard);
            Yii::$app->session->setFlash('success', 'Выставка удалена' . ($hard ? ' полностью из базы' : ''));
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['active']);
    }

    /**
     * Отслеживание нажатия кнопок действий с выбранными элементами (Удалить выбранные,продлить и так далее)
     * @param bool $hardRemove флаг удалять полностью из базы или нет
     */
    private function selectedActionHandle($hardRemove = false): void
    {
        $ids = (array) Yii::$app->request->post('ids');
        $action = Yii::$app->request->post('action');
        if (count($ids)) {
            if ($action == 'remove') {
                $count = $this->service->massRemove($ids, $hardRemove);
                Yii::$app->session->setFlash('info', 'Удалено выставок: ' . $count);
            } else if ($action == 'publish') {
                $this->service->publish($ids);
                Yii::$app->session->setFlash('info', 'Размещено выставок: ' . count($ids));
            }
        }
    }

    /**
     * @param integer $id
     * @return Expo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    private function findModel($id)
    {
        if (($model = Expo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Выставка не найдена.');
        }
    }
}