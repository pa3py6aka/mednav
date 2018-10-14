<?php

namespace backend\controllers\news;


use backend\forms\NewsSearch;
use core\actions\CategorySelectAction;
use core\actions\DeletePhotoAction;
use core\actions\MovePhotoAction;
use core\actions\UploadAction;
use core\entities\News\News;
use core\entities\News\NewsCategory;
use core\forms\News\NewsForm;
use core\forms\manage\PhotosForm;
use core\repositories\News\NewsRepository;
use core\useCases\NewsService;
use core\useCases\manage\News\NewsPhotoService;
use Yii;
use yii\base\Module;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class NewsController extends Controller
{
    private $service;
    private $repository;

    public function __construct(string $id, Module $module, NewsService $service, NewsRepository $repository, array $config = [])
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
                'entity' => NewsCategory::class,
            ],
            'upload' => [
                'class' => UploadAction::class,
            ],
            'move-photo' => [
                'class' => MovePhotoAction::class,
                'entityClass' => News::class,
                'serviceClass' => NewsPhotoService::class,
            ],
            'delete-photo' => [
                'class' => DeletePhotoAction::class,
                'entityClass' => News::class,
                'serviceClass' => NewsPhotoService::class,
            ],
        ];
    }

    /**
     * Листинг размещённых статей.
     * @return mixed
     */
    public function actionActive()
    {
        $this->selectedActionHandle();

        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('active', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Листинг статей на проверку.
     * @return mixed
     */
    public function actionModeration()
    {
        $this->selectedActionHandle();

        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'moderation');

        return $this->render('moderation', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Листинг удалённых статей.
     * @return mixed
     */
    public function actionDeleted()
    {
        $this->selectedActionHandle(true);

        $searchModel = new NewsSearch();
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
        $news = $this->findModel($id);

        $photosForm = new PhotosForm();
        if ($photosForm->load(Yii::$app->request->post()) && $photosForm->validate()) {
            try {
                $this->service->addPhotos($news->id, $photosForm);
                Yii::$app->session->setFlash('success', 'Фотографии загружены');
                return $this->redirect(['view', 'id' => $news->id, 'tab' => 'photos']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('view', [
            'model' => $news,
            'photosForm' => $photosForm,
            'tab' => $tab,
        ]);
    }

    public function actionCreate()
    {
        $form = new NewsForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $news = $this->service->create($form);
                return $this->redirect(['view', 'id' => $news->id]);
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
        $news = $this->findModel($id);
        $form = new NewsForm($news);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $this->service->edit($id, $form);
            Yii::$app->session->setFlash('success', 'Новость обновлена');
            return $this->redirect(['view', 'id' => $news->id]);
        }

        return $this->render('update', [
            'model' => $form,
        ]);
    }

    public function actionDelete($id, $hard = 0)
    {
        try {
            $this->service->remove($id, !(bool) $hard);
            Yii::$app->session->setFlash('success', 'Новость удалена' . ($hard ? ' полностью из базы' : ''));
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
                Yii::$app->session->setFlash('info', 'Удалено новостей: ' . $count);
            } else if ($action == 'publish') {
                $this->service->publish($ids);
                Yii::$app->session->setFlash('info', 'Размещено новостей: ' . count($ids));
            }
        }
    }

    /**
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    private function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Статья не найдена.');
        }
    }
}