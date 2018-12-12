<?php

namespace backend\controllers\board;


use core\components\TreeManager\TreeManageActions;
use core\entities\Board\BoardCategory;
use core\entities\Board\BoardCategoryRegion;
use core\forms\manage\Board\BoardCategoryForm;
use core\forms\manage\Board\BoardCategoryRegionForm;
use core\useCases\manage\Board\BoardCategoryManageService;
use Yii;
use yii\base\Module;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class CategoryController extends Controller
{
    private $service;

    public function __construct($id, Module $module, BoardCategoryManageService $service, array $config = [])
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
            'tree-manage' => [
                'class' => TreeManageActions::class,
                'entityClass' => BoardCategory::class,
                'url' => '/board/category',
            ],
        ];
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => BoardCategory::find()->roots(),
            'sort' => ['defaultOrder' => ['lft' => SORT_ASC]],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $form = new BoardCategoryForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $category = $this->service->create($form);
                Yii::$app->session->setFlash('success', 'Раздел добавлен');
                return $this->redirect(['update', 'id' => $category->id, 'tab' => 'geo']);
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
        $category = $this->findModel($id);
        $form = new BoardCategoryForm($category);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($category->id, $form);
                Yii::$app->session->setFlash('success', 'Данные обновлены');
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        // Привязка к регионам
        if (Yii::$app->request->post('attach')) {
            try {
                $this->service->attachRegions($category->id, Yii::$app->request->post('rId', []));
                Yii::$app->session->setFlash('success', 'Привязка к регионам обновлена');
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'category' => $category,
            'model' => $form,
            'tab' => Yii::$app->request->getQueryParam('tab', 'home'),
        ]);
    }

    public function actionRegionSettings()
    {
        $categoryId = (int) Yii::$app->request->post('entityId');
        $regionId = (int) Yii::$app->request->post('regionId');

        $categoryRegion = BoardCategoryRegion::find()->where(['category_id' => $categoryId, 'geo_id' => $regionId])->limit(1)->one();
        $form = new BoardCategoryRegionForm($categoryRegion);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->saveRegion($categoryId,$regionId, $form);
                Yii::$app->session->setFlash('success', 'Настройки региона сохранены');
                return $this->redirect(Yii::$app->request->referrer);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->renderAjax('@backend/views/common/_region_form', [
            'model' => $form,
            'categoryId' => $categoryId,
            'regionId' => $regionId,
        ]);
    }

    public function actionDelete($id)
    {
        try {
            $this->service->remove($id);
            Yii::$app->session->setFlash('success', 'Раздел удалён');
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = BoardCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
