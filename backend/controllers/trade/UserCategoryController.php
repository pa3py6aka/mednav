<?php

namespace backend\controllers\trade;


use backend\forms\TradeSearch;
use backend\forms\TradeUserCategorySearch;
use core\actions\CategorySelectAction;
use core\entities\Trade\TradeCategory;
use core\entities\Trade\TradeUserCategory;
use core\forms\manage\Trade\TradeUserCategoryForm;
use core\useCases\manage\Trade\TradeManageService;
use Yii;
use yii\base\Module;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class UserCategoryController extends Controller
{
    private $service;

    public function __construct(string $id, Module $module, TradeManageService $service, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function actions(): array
    {
        return [
            'select-category' => [
                'class' => CategorySelectAction::class,
                'entity' => TradeCategory::class,
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new TradeUserCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        Yii::$app->user->setReturnUrl(['/trade/user-category/view', 'id' => $id]);
        $tradesSearch = new TradeSearch();
        $tradesProvider = $tradesSearch->search(Yii::$app->request->queryParams, 'user-category', $id);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'tradesProvider' => $tradesProvider,
            'tradesSearch' => $tradesSearch,
        ]);
    }


    public function actionCreate()
    {
        $userId = Yii::$app->request->get('userId', Yii::$app->user->id);
        $form = new TradeUserCategoryForm();
        $form->userId = $userId;

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->createUserCategory($form->userId, $form);
                Yii::$app->session->setFlash('success', 'Пользовательская категория успешно добавлена');
                if (strpos(Yii::$app->user->getReturnUrl(), 'trade/trade/') !== false) {
                    return $this->redirect(Yii::$app->user->getReturnUrl());
                }
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
        $userCategory = $this->findModel($id);
        $form = new TradeUserCategoryForm($userCategory);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->editUserCategory($userCategory, $form);
                Yii::$app->session->setFlash('success', 'Данные сохранены');
                return $this->redirect(['view', 'id' => $id]);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'userCategory' => $userCategory,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id): TradeUserCategory
    {
        if (($model = TradeUserCategory::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}