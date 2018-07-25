<?php

namespace core\useCases;


use core\entities\Dialog\Dialog;
use core\entities\Dialog\Message;
use core\repositories\DialogRepository;
use core\services\TransactionManager;
use frontend\widgets\message\NewMessageForm;
use Yii;

class DialogService
{
    private $repository;
    private $transaction;

    public function __construct(DialogRepository $repository, TransactionManager $transaction)
    {
        $this->repository = $repository;
        $this->transaction = $transaction;
    }

    public function sendNew(NewMessageForm $form): void
    {
        $this->transaction->wrap(function () use ($form) {
            if (!Yii::$app->user->isGuest) {
                if (!$dialog = $this->repository->find(Yii::$app->user->id, $form->toId, $form->subject)) {
                    $dialog = Dialog::create(Yii::$app->user->id, $form->toId, $form->subject);
                    $this->repository->save($dialog);
                }
                $message = Message::create($dialog->id, Yii::$app->user->id, $form->text);
            } else {
                $dialog = Dialog::create(null, $form->toId, $form->subject, $form->name, $form->phone, $form->email);
                $this->repository->save($dialog);
                $message = Message::create($dialog->id, null, $form->text);
            }
            $this->repository->saveMessage($message);
        });
    }

    public function sendFromChat($dialogId, $userId, $text): Message
    {
        $message = Message::create($dialogId, $userId, $text);
        $this->repository->saveMessage($message);
        return $message;
    }

    public function markAsRead($dialogId, $userId)
    {
        $this->repository->markAsRead($dialogId, $userId);
    }

}