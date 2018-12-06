<?php

namespace core\repositories;


use core\entities\Contact;
use core\entities\Dialog\Dialog;
use core\entities\Dialog\Message;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class DialogRepository
{
    public function get($id): Dialog
    {
        if (!$dialog = Dialog::findOne($id)) {
            throw new NotFoundHttpException('Диалог не найден.');
        }
        return $dialog;
    }

    public function find($userFromId, $userToId, $subject): ?Dialog
    {
        return Dialog::find()
            ->where(['user_from' => $userFromId, 'user_to' => $userToId, 'subject' => $subject])
            ->limit(1)
            ->one();
    }

    public function getUserDialogs($userId): ActiveDataProvider
    {
        $query = Dialog::find()
            ->alias('d')
            ->addSelect(['d.*', 'SUM(IF(m.`status`=0 AND (m.user_id<>'.$userId.' OR m.user_id is NULL), 1, 0)) as not_read'])
            ->with('userFrom', 'userTo', 'lastMessage')
            ->leftJoin('{{%messages}} m', 'm.dialog_id=d.id')
            ->where(['or', ['d.user_from' => $userId], ['d.user_to' => $userId]])
            ->andWhere(['>', 'm.id', 0])
            ->groupBy(['d.id']);

        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['date' => SORT_DESC],
                'attributes' => [
                    'date' => [
                        'asc' => ['not_read' => SORT_DESC, 'MAX(m.created_at)' => SORT_ASC],
                        'desc' => ['not_read' => SORT_DESC, 'MAX(m.created_at)' => SORT_DESC],
                    ],
                ],
            ],
            'pagination' => [
                'pageSizeLimit' => [25, 250],
                'defaultPageSize' => 25,
                'forcePageParam' => false,
            ]
        ]);
    }

    public function getDialogMessages(Dialog $dialog): ActiveDataProvider
    {
        $query = $dialog->getMessages()->orderBy(['id' => SORT_ASC]);
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => false,
            /*'pagination' => [
                'pageSizeLimit' => [25, 250],
                'defaultPageSize' => 25,
                'forcePageParam' => false,
            ]*/
        ]);
    }

    public function markAsRead($dialogId, $userId)
    {
        Message::updateAll(['status' => 1], ['dialog_id' => $dialogId, 'user_id' => $userId]);
    }

    public function save(Dialog $dialog): void
    {
        if (!$dialog->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function saveMessage(Message $message): void
    {
        if (!$message->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function remove(Dialog $dialog): void
    {
        if (!$dialog->delete()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }

    public function getUserContacts($userId): ActiveDataProvider
    {
        $query = Contact::find()->where(['user_id' => $userId]);
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pageSizeLimit' => [25, 250],
                'defaultPageSize' => 25,
                'forcePageParam' => false,
            ]
        ]);
    }

    public function getUserContact($contactId, $userId): Contact
    {
        if (!$contact = Contact::find()->where(['user_id' => $userId, 'id' => $contactId])->limit(1)->one()) {
            throw new NotFoundHttpException("Контакт не найден");
        }
        return $contact;
    }

    public function hasContact($userId, $contactId): bool
    {
        return Contact::find()->where(['user_id' => $userId, 'contact_id' => $contactId])->exists();
    }

    public function saveContact(Contact $contact): void
    {
        if (!$contact->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function removeContact(Contact $contact): void
    {
        if (!$contact->delete()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }
}