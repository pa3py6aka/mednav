<?php

namespace core\repositories;


use core\entities\Dialog\Dialog;
use core\entities\Dialog\Message;
use yii\data\ActiveDataProvider;

class DialogRepository
{
    public function get($id): Dialog
    {
        if (!$dialog = Dialog::findOne($id)) {
            throw new NotFoundException('Диалог не найден.');
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
        /*$query = Dialog::find()
            ->where([
                'or',
                ['user_to' => $userId],
                ['user_from' => $userId],
            ])->andWhere(['status' => Dialog::STATUS_ACTIVE]);*/

        $query = Dialog::find()
            ->alias('d')
            ->addSelect(['d.*', 'SUM(IF(m.`status`=0 AND (m.user_id<>'.$userId.' OR m.user_id is NULL), 1, 0)) as not_read'])
            ->with('userFrom', 'userTo', 'lastMessage')
            ->leftJoin('{{%messages}} m', 'm.dialog_id=d.id')
            ->where(['or', ['d.user_from' => $userId], ['user_to' => $userId]])
            ->orderBy(['not_read' => SORT_DESC,'d.id' => SORT_DESC])
            ->groupBy('d.id');

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
}