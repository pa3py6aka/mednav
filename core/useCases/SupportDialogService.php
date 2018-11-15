<?php

namespace core\useCases;


use core\components\Settings;
use core\entities\SupportDialog\SupportDialog;
use core\entities\SupportDialog\SupportMessage;
use core\forms\SupportMessageForm;
use core\jobs\SendMailJob;
use core\repositories\SupportDialogRepository;
use core\services\TransactionManager;
use Yii;

class SupportDialogService
{
    private $repository;
    private $transaction;

    public function __construct(SupportDialogRepository $repository, TransactionManager $transaction)
    {
        $this->repository = $repository;
        $this->transaction = $transaction;
    }

    public function sendNew(SupportMessageForm $form, $dialogId = null, $fromSupport = false): SupportDialog
    {
        $this->transaction->wrap(function () use ($form, $dialogId, $fromSupport, &$dialog) {
            if (!$dialogId) {
                $dialog = SupportDialog::create(Yii::$app->user->id, $form->subject);
                $this->repository->save($dialog);
            } else {
                $dialog = $this->repository->get($dialogId);
            }

            $message = SupportMessage::create(
                $dialog->id,
                $fromSupport ? null : Yii::$app->user->id,
                $form->text
            );

            $this->repository->saveMessage($message);

            if ($fromSupport) {
                $this->sendNotificationToEmail($message);
            }
        });

        return $dialog;
    }

    public function sendFromChat($dialogId, $userId, $text): SupportMessage
    {
        $message = SupportMessage::create($dialogId, $userId, $text);
        $this->repository->saveMessage($message);
        if (!$userId && !$message->dialog->user->isOnline()) {
            $this->sendNotificationToEmail($message);
        }
        return $message;
    }

    public function sendNotificationToEmail(SupportMessage $message)
    {
        Yii::$app->queue->push(new SendMailJob([
            'view' => 'message-from-support',
            'params' => ['message' => $message],
            'to' => [$message->dialog->user->getEmail() => $message->dialog->user->getVisibleName()],
            'subject' => '[' . Yii::$app->settings->get(Settings::GENERAL_EMAIL_FROM) . '] Сообщение - ' . $message->dialog->subject
        ]));
    }

    public function markAsRead($dialogId, $userId = null)
    {
        $this->repository->markAsRead($dialogId, $userId);
    }

}