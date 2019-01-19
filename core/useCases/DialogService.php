<?php

namespace core\useCases;


use Codeception\Lib\Di;
use core\components\Settings;
use core\entities\Contact;
use core\entities\Dialog\Dialog;
use core\entities\Dialog\Message;
use core\jobs\SendMailJob;
use core\repositories\Company\CompanyRepository;
use core\repositories\DialogRepository;
use core\repositories\UserRepository;
use core\services\Mailer;
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
            $toUser = (new UserRepository())->get($form->toId);
            if (!$toUser->isActive()) {
                throw new \DomainException('Пользователь удалён или заблокирован, отправка сообщений невозможна.');
            }

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
            $this->sendNotificationToEmail($message);
        });
    }

    public function sendFromChat($dialogId, $userId, $text, $subject = null): Message
    {
        $message = Message::create($dialogId, $userId, $text);

        if (!$message->dialog->getInterlocutor($message->user_id)->isActive()) {
            throw new \DomainException('Пользователь удалён или заблокирован, отправка сообщений невозможна.');
        }

        $this->transaction->wrap(function () use ($message, $subject) {
            if (!$message->dialog->subject && $subject) {
                $dialog = $message->dialog;
                $dialog->subject = $subject;
                $this->repository->save($dialog);
            }
            $this->repository->saveMessage($message);
        });

        if (!$message->dialog->getInterlocutor($message->user_id)->isOnline()) {
            $this->sendNotificationToEmail($message, true);
        }
        return $message;
    }

    public function sendNotificationToEmail(Message $message, $isFromChat = false)
    {
        $toUser = $message->dialog->getInterlocutor($message->user_id);
        $params = ['message' => $message];
        !$isFromChat ? $params['page'] = Yii::$app->request->getReferrer() : null;

        /*Mailer::send(
            [$toUser->getEmail() => $toUser->getVisibleName()],
            '[' . Yii::$app->settings->get(Settings::GENERAL_EMAIL_FROM) . '] Сообщение - ' . $message->dialog->subject,
            $isFromChat ? 'message-from-chat' : ($message->user_id ? 'message' : 'message-from-unregistered'),
            $params
        );*/

        Yii::$app->queue->push(new SendMailJob([
            'view' => $isFromChat ? 'message-from-chat' : ($message->user_id ? 'message' : 'message-from-unregistered'),
            'params' => $params,
            'to' => [$toUser->getEmail() => $toUser->getVisibleName()],
            'subject' => '[' . Yii::$app->settings->get(Settings::GENERAL_EMAIL_FROM) . '] Сообщение - ' . $message->dialog->subject
        ]));
    }

    public function markAsRead($dialogId, $userId)
    {
        $this->repository->markAsRead($dialogId, $userId);
    }

    public function addContact($contactId, $userId): void
    {
        $user = (new UserRepository())->get($contactId);
        if ($this->repository->hasContact($userId, $contactId)) {
            throw new \DomainException("Компания <b>{$user->getVisibleName()}</b> уже находится в вашем списке контактов.");
        }
        $contact = Contact::create($userId, $contactId);
        $this->repository->saveContact($contact);
    }

    public function getOrCreateContactDialog(Contact $contact): Dialog
    {
        if (!$dialog = Dialog::find()->where([
            'user_from' => [$contact->contact_id, $contact->user_id],
            'user_to' => [$contact->contact_id, $contact->user_id],
            'subject' => ''
        ])->limit(1)->one()) {
            // Создаём диалог
            $dialog = Dialog::create($contact->user_id, $contact->contact_id, '');
            $this->repository->save($dialog);
        }

        return $dialog;
    }

    public function remove($id): void
    {
        $news = $this->repository->get($id);
        $this->repository->remove($news);
    }
}