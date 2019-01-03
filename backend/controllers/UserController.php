<?php

namespace backend\controllers;


use core\components\Settings;
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
    public function actionActive()
    {
        $this->selectedActionHandle();

        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('active', [
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

    /**
     * Листинг пользователей на модерации
     * @return mixed
     */
    public function actionModeration()
    {
        $this->selectedActionHandle(true);

        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'moderation');

        return $this->render('moderation', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Листинг удалённых пользователей.
     * @return mixed
     */
    public function actionDeleted()
    {
        $this->selectedActionHandle(true);

        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'deleted');

        return $this->render('deleted', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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

        return $this->redirect(['active']);
    }

    public function actionMessage($id)
    {
        $user = $this->findModel($id);
        $form = new MessageToUserForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            Mailer::send(
                $user->email,
                '[' . Yii::$app->settings->get(Settings::GENERAL_EMAIL_FROM) . ': Сообщение от администратора',
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
     * @param int $hard
     * @return mixed
     */
    public function actionDelete($id, $hard = 0)
    {
        try {
            $this->service->remove($id, !(bool) $hard);
            Yii::$app->session->setFlash('success', 'Профиль удалён' . ($hard ? ' полностью из базы' : ''));
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect(['view', 'id' => $id]);
        }

        return $this->redirect(['active']);
    }

    public function actionGetUser(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = (int) Yii::$app->request->post('id');
        if (($user = User::findOne($id)) !== null) {
            return [
                'result' => 'success',
                'name' => $user->getVisibleName(),
                'url' => Url::to(['/user/view', 'id' => $user->id]),
                'geo' => $user->isCompany() && $user->isCompanyActive()
                    ? $user->company->getGeo()->select(['id', 'name'])->asArray()->one()
                    : $user->getGeo()->select(['id', 'name'])->asArray()->one(),
            ];
        }
        return ['result' => 'error', 'message' => 'Пользователь не найден'];
    }

    /**
     * Отслеживание нажатия кнопок действий с выбранными элементами (Удалить выбранные,продлить и так далее)
     * @param bool $hardRemove флаг удалять полностью из базы или нет
     */
    private function selectedActionHandle($hardRemove = false): void
    {
        $ids = (array) Yii::$app->request->post('ids');
        $action = Yii::$app->request->post('action');
        if (\count($ids)) {
            if ($action === 'remove') {
                $count = $this->service->massRemove($ids, $hardRemove);
                Yii::$app->session->setFlash('info', 'Удалено пользователей: ' . $count);
            } else if ($action === 'publish') {
                $this->service->activate($ids);
                Yii::$app->session->setFlash('info', 'Размещено пользователей: ' . \count($ids));
            }
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): User
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Пользователь не найден.');
    }
}
