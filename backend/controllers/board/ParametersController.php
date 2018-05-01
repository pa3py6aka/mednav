<?php

namespace backend\controllers\board;


use core\entities\Board\BoardParameter;
use core\entities\Board\BoardParameterOption;
use core\forms\manage\Board\BoardParameterForm;
use core\forms\manage\Board\BoardParameterOptionForm;
use core\useCases\manage\Board\BoardParameterManageService;
use Yii;
use yii\base\Module;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ParametersController extends Controller
{
    private $service;

    public function __construct($id, Module $module, BoardParameterManageService $service, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function actionIndex()
    {
        $provider = new ActiveDataProvider([
            'query' => BoardParameter::find()->orderBy(['sort' => SORT_ASC])
        ]);

        return $this->render('index', [
            'provider' => $provider,
        ]);
    }

    public function actionView($id)
    {
        $parameter = $this->findModel($id);
        $optionsProvider = new ActiveDataProvider([
            'query' => $parameter->getBoardParameterOptions()->orderBy(['sort' => SORT_ASC]),
            'pagination' => false,
        ]);

        return $this->render('view', [
            'parameter' => $parameter,
            'optionsProvider' => $optionsProvider,
        ]);
    }

    public function actionCreate()
    {
        $form = new BoardParameterForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $parameter = $this->service->create($form);
                return $this->redirect(['view', 'id' => $parameter->id]);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('parameter-form', [
            'model' => $form,
        ]);
    }

    public function actionUpdate($id)
    {
        $parameter = $this->findModel($id);
        $form = new BoardParameterForm($parameter);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($id, $form);
                Yii::$app->session->setFlash('success', 'Данные сохранены');
                return $this->redirect(['view', 'id' => $parameter->id]);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('parameter-form', [
            'model' => $form,
        ]);
    }

    public function actionDelete($id)
    {
        try {
            $this->service->remove($id);
            Yii::$app->session->setFlash('success', 'Параметр удалён');
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }

    public function actionOptionCreate($id)
    {
        $parameter = $this->findModel($id);
        $form = new BoardParameterOptionForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $option = $this->service->createOption($id, $form);
                return $this->redirect(['view', 'id' => $parameter->id]);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('option-form', ['model' => $form, 'parameterId' => $id]);
    }

    public function actionOptionUpdate($id)
    {
        $option = $this->findOption($id);
        $form = new BoardParameterOptionForm($option);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->editOption($id, $form);
                Yii::$app->session->setFlash('success', 'Данные сохранены');
                return $this->redirect(['view', 'id' => $option->parameter_id]);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('option-form', ['model' => $form, 'parameterId' => $option->parameter_id]);
    }

    public function actionOptionDelete($id)
    {
        $option = $this->findOption($id);
        try {
            $this->service->removeOption($id);
            Yii::$app->session->setFlash('success', 'Опция удалена');
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['view', 'id' => $option->parameter_id]);
    }

    protected function findModel($id)
    {
        if (($model = BoardParameter::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Параметр не найден.');
        }
    }

    protected function findOption($id)
    {
        if (($model = BoardParameterOption::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Опция не найдена.');
        }
    }
}