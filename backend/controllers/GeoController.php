<?php

namespace backend\controllers;


use backend\forms\GeoSearch;
use core\entities\Geo;
use core\forms\manage\Geo\GeoForm;
use core\forms\manage\Geo\GeoSettingsForm;
use core\useCases\manage\GeoManageService;
use Yii;
use yii\base\Module;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class GeoController extends Controller
{
    private $service;

    public function __construct($id, Module $module, GeoManageService $service, array $config = [])
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

    public function actionIndex()
    {
        $searchModel = new GeoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $form = new GeoForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $geo = $this->service->create($form);
                Yii::$app->session->setFlash('success', 'Регион добавлен');
                return $this->redirect(['index']);
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
        $geo = $this->findModel($id);
        $form = new GeoForm($geo);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($geo->id, $form);
                Yii::$app->session->setFlash('success', 'Данные обновлены');
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'geo' => $geo,
            'model' => $form,
        ]);
    }

    public function actionSettings()
    {
        $form = new GeoSettingsForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            if (Yii::$app->settings->saveForm($form)) {
                Yii::$app->session->setFlash("success", "Настройки успешно сохранены");
                return $this->render('settings', ['model' => $form]);
            }
        }

        return $this->render('settings', ['model' => $form]);
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
        if (($model = Geo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
