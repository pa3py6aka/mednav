<?php

namespace backend\controllers\company;


use core\components\TreeManager\TreeManageActions;
use core\entities\Company\CompanyCategory;
use core\entities\Company\CompanyCategoryRegion;
use core\forms\manage\Company\CompanyCategoryRegionForm;
use core\forms\manage\Company\CompanyCategoryForm;
use core\useCases\manage\Company\CompanyCategoryManageService;
use Yii;
use yii\base\Module;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class CategoryController extends Controller
{
    private $service;

    public function __construct($id, Module $module, CompanyCategoryManageService $service, array $config = [])
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
                'entityClass' => CompanyCategory::class,
                'url' => '/company/category',
            ],
        ];
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CompanyCategory::find()->roots(),
            'sort' => ['defaultOrder' => ['lft' => SORT_ASC]],
            'pagination' => false,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $form = new CompanyCategoryForm();

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
        $form = new CompanyCategoryForm($category);

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

        $categoryRegion = CompanyCategoryRegion::find()->where(['category_id' => $categoryId, 'geo_id' => $regionId])->limit(1)->one();
        $form = new CompanyCategoryRegionForm($categoryRegion);

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
            Yii::$app->session->setFlash('success', 'Регион удалён');
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = CompanyCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
