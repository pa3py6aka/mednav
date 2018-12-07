<?php

namespace frontend\controllers\user;


use core\access\Rbac;
use core\forms\SupportMessageForm;
use core\repositories\SupportDialogRepository;
use core\useCases\SupportDialogService;
use frontend\widgets\message\SendMessageAction;
use Yii;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class SupportController extends Controller
{
    private $service;
    private $repository;
    private $_user;

    public function __construct(string $id, Module $module, SupportDialogService $service, SupportDialogRepository $repository, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->repository = $repository;
        $this->_user = Yii::$app->user->identity;
        $this->view->params['user'] = $this->_user;
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'except' => ['send-message'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [Rbac::ROLE_USER],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'send-message' => ['post'],
                ]
            ]
        ];
    }

    public function actions()
    {
        return [
            'send-message' => SendMessageAction::class,
        ];
    }

    public function actionDialogs()
    {
        $provider = $this->repository->getUserDialogs($this->_user->id);

        return $this->render('dialogs', [
            'provider' => $provider,
        ]);
    }

    public function actionCreate()
    {
        $form = new SupportMessageForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $dialog = $this->service->sendNew($form);
                return $this->redirect(['view', 'id' => $dialog->id]);
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('create', ['model' => $form]);
    }

    public function actionView($id)
    {
        $dialog = $this->repository->get($id);
        if ($this->_user->id != $dialog->user_id) {
            throw new ForbiddenHttpException('Это чужая переписка!');
        }

        $provider = $this->repository->getDialogMessages($dialog);
        $this->service->markAsRead($dialog->id);

        if ($text = Yii::$app->request->post('message')) {
            try {
                $message = $this->service->sendFromChat($dialog->id, $this->_user->id, $text);
                return $this->asJson([
                    'result' => 'success',
                    'message' => $this->renderPartial('_message-row', ['message' => $message])
                ]);
            } catch (\DomainException $e) {
                return $this->asJson(['result' => 'error', 'message' => $e->getMessage()]);
            }
        }

        return $this->render('view', [
            'dialog' => $dialog,
            'provider' => $provider,
            'user' => $this->_user,
        ]);
    }
}