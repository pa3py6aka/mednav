<?php

namespace backend\controllers\brand;


use backend\forms\BrandSearch;
use core\actions\CategorySelectAction;
use core\actions\DeletePhotoAction;
use core\actions\MovePhotoAction;
use core\actions\UploadAction;
use core\entities\Brand\Brand;
use core\entities\Brand\BrandCategory;
use core\forms\Brand\BrandForm;
use core\forms\manage\PhotosForm;
use core\repositories\Brand\BrandRepository;
use core\useCases\BrandService;
use core\useCases\manage\Brand\BrandPhotoService;
use Yii;
use yii\base\Module;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class BrandController extends Controller
{
    private $service;
    private $repository;

    public function __construct(string $id, Module $module, BrandService $service, BrandRepository $repository, array $config = [])
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
                'entity' => BrandCategory::class,
            ],
            'upload' => [
                'class' => UploadAction::class,
            ],
            'move-photo' => [
                'class' => MovePhotoAction::class,
                'entityClass' => Brand::class,
                'serviceClass' => BrandPhotoService::class,
            ],
            'delete-photo' => [
                'class' => DeletePhotoAction::class,
                'entityClass' => Brand::class,
                'serviceClass' => BrandPhotoService::class,
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

        $searchModel = new BrandSearch();
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

        $searchModel = new BrandSearch();
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

        $searchModel = new BrandSearch();
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
        $form = new BrandForm();

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
        $form = new BrandForm($brand);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $this->service->edit($id, $form);
            Yii::$app->session->setFlash('success', 'Бренд обновлён.');
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
            Yii::$app->session->setFlash('success', 'Бренд удалён' . ($hard ? ' полностью из базы' : ''));
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
                Yii::$app->session->setFlash('info', 'Удалено брендов: ' . $count);
            } else if ($action == 'publish') {
                $this->service->publish($ids);
                Yii::$app->session->setFlash('info', 'Размещено брендов: ' . count($ids));
            }
        }
    }

    /**
     * @param integer $id
     * @return Brand the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    private function findModel($id)
    {
        if (($model = Brand::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Бренд не найден.');
        }
    }
}