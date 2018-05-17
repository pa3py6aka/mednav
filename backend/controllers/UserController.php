<?php

namespace backend\controllers;


use core\forms\manage\User\MessageToUserForm;
use core\forms\manage\User\UserCreateForm;
use core\forms\manage\User\UserEditForm;
use core\forms\manage\User\UserSettingsForm;
use core\services\Mailer;
use core\useCases\manage\UserManageService;
use Yii;
use core\entities\User\User;
use backend\forms\UserSearch;
use yii\base\Module;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    private $service;

    public function __construct($id, Module $module, UserManageService $service, array $config = [])
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
                    'get-user' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new UserCreateForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $user = $this->service->create($form);
                return $this->redirect(['view', 'id' => $user->id]);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $form,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $user = $this->findModel($id);
        $form = new UserEditForm($user);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($user->id, $form);
                Yii::$app->session->setFlash('success', 'Данные обновлены');
                return $this->redirect(['view', 'id' => $user->id]);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
        ]);
    }

    public function actionConfirm($id)
    {
        $user = $this->findModel($id);

        try {
            $this->service->updateStatus($user, User::STATUS_ACTIVE);
            Yii::$app->session->setFlash('success', 'Профиль размещён');
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect(['view', 'id' => $id]);
        }

        return $this->redirect(['index']);
    }

    public function actionMessage($id)
    {
        $user = $this->findModel($id);
        $form = new MessageToUserForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            Mailer::send(
                $user->email,
                Yii::$app->params['siteName'] . ': Сообщение от администратора',
                'manage/message-to-user',
                ['user' => $user, 'message' => $form->message]
            );
            Yii::$app->session->setFlash('success', 'Сообщение отправлено');
            return $this->redirect(['view', 'id' => $id]);
        }

        return $this->render('message', ['model' => $form]);
    }

    public function actionSettings()
    {
        $form = new UserSettingsForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            if (Yii::$app->settings->saveForm($form)) {
                Yii::$app->session->setFlash("success", "Настройки успешно сохранены");
                return $this->render('settings', ['model' => $form]);
            }
        }

        return $this->render('settings', ['model' => $form]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $user = $this->findModel($id);

        try {
            $this->service->updateStatus($user, User::STATUS_DELETED);
            Yii::$app->session->setFlash('success', 'Профиль удалён');
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect(['view', 'id' => $id]);
        }

        return $this->redirect(['index']);
    }

    public function actionGetUser()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = (int) Yii::$app->request->post('id');
        if (($user = User::findOne($id)) !== null) {
            return ['result' => 'success', 'name' => $user->getVisibleName(), 'url' => Url::to(['/user/view', 'id' => $user->id])];
        }
        return ['result' => 'error', 'message' => 'Пользователь не найден'];
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
