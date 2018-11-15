<?php

namespace backend\controllers;


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

class DialogController extends Controller
{
    private $service;
    private $repository;

    public function __construct(string $id, Module $module, SupportDialogService $service, SupportDialogRepository $repository, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->repository = $repository;
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'send-message' => ['post'],
                ]
            ]
        ];
    }

    public function actionDialogs()
    {
        $provider = $this->repository->getAllDialogs();

        return $this->render('dialogs', [
            'provider' => $provider,
        ]);
    }

    public function actionView($id)
    {
        $dialog = $this->repository->get($id);

        $provider = $this->repository->getDialogMessages($dialog);
        $this->service->markAsRead($dialog->id, $dialog->user_id);

        if ($text = Yii::$app->request->post('message')) {
            try {
                $message = $this->service->sendFromChat($dialog->id, null, $text);
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
        ]);
    }
}