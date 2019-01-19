<?php

namespace frontend\controllers\user;


use core\access\Rbac;
use core\entities\Dialog\Dialog;
use core\repositories\DialogRepository;
use core\repositories\UserRepository;
use core\useCases\DialogService;
use frontend\widgets\message\SendMessageAction;
use Yii;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

class MessageController extends Controller
{
    private $service;
    private $repository;
    private $_user;

    public function __construct(string $id, Module $module, DialogService $service, DialogRepository $repository, array $config = [])
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
                    'add-contact' => ['post'],
                    'delete' => ['post'],
                    'contact-remove' => ['post'],
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
        $this->layout = '@frontend/views/user/message/layout';
        $provider = $this->repository->getUserDialogs($this->_user->id);
        $this->view->params['pagination'] = $provider->pagination;
        $this->view->params['tab'] = 'dialogs';

        return $this->render('dialogs', [
            'provider' => $provider,
        ]);
    }

    public function actionView($id)
    {
        $dialog = $this->repository->get($id);
        if (!\in_array($this->_user->id, [$dialog->user_from, $dialog->user_to], true)) {
            throw new ForbiddenHttpException('Это чужая переписка!');
        }

        $provider = $this->repository->getDialogMessages($dialog);
        $this->service->markAsRead($dialog->id, $dialog->getInterlocutorId($this->_user->id));

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

    public function actionDelete($id)
    {
        try {
            $this->service->remove($id);
            Yii::$app->session->setFlash('success', 'Диалог удалён');
        } catch (\DomainException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['dialogs']);
    }

    public function actionAddContact()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $contactId = Yii::$app->request->post('contactId');
        $user = (new UserRepository())->get($contactId);
        try {
            $this->service->addContact($user->id, Yii::$app->user->id);
            return ['result' => 'success', 'message' => "Компания <b>{$user->getVisibleName()}</b> добавлена в ваш список контактов."];
        } catch (\DomainException $e) {
            return ['result' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function actionContacts()
    {
        $this->layout = '@frontend/views/user/message/layout';
        $this->view->params['tab'] = 'contacts';
        $provider = $this->repository->getUserContacts($this->_user->id);

        return $this->render('contacts', [
            'provider' => $provider,
        ]);
    }

    public function actionContact($id)
    {
        $contact = $this->repository->getUserContact($id, Yii::$app->user->id);
        $dialog = $this->service->getOrCreateContactDialog($contact);
        return $this->redirect(['view', 'id' => $dialog->id]);
    }

    public function actionContactRemove($id)
    {
        $contact = $this->repository->getUserContact($id, Yii::$app->user->id);
        $contact->delete();
        Yii::$app->session->setFlash('success', 'Контакт удалён.');
        return $this->redirect(['contacts']);
    }
}