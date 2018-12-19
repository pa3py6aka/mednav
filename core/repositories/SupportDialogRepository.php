<?php

namespace core\repositories;


use core\entities\SupportDialog\SupportDialog;
use core\entities\SupportDialog\SupportMessage;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class SupportDialogRepository
{
    public function get($id): SupportDialog
    {
        if (!$dialog = SupportDialog::findOne($id)) {
            throw new NotFoundHttpException('Диалог не найден.');
        }
        return $dialog;
    }

    public function getUserDialogs($userId): ActiveDataProvider
    {
        $query = SupportDialog::find()
            ->alias('d')
            ->addSelect(['d.*', 'SUM(IF(m.`status`=0 AND (m.user_id<>'.$userId.' OR m.user_id is NULL), 1, 0)) as not_read', 'MAX(IF(m.`status`=0 AND (m.user_id<>'.$userId.' OR m.user_id is NULL), 1, 0)) as not_read_order'])
            ->with('lastMessage')
            ->leftJoin('{{%support_messages}} m', 'm.dialog_id=d.id')
            ->where(['d.user_id' => $userId])
            //->orderBy(['not_read' => SORT_DESC,'d.id' => SORT_DESC])
            ->groupBy('d.id');

        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['date' => SORT_DESC],
                'attributes' => [
                    'date' => [
                        'asc' => ['not_read_order' => SORT_DESC, 'MAX(m.created_at)' => SORT_ASC],
                        'desc' => ['not_read_order' => SORT_DESC, 'MAX(m.created_at)' => SORT_DESC],
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

    public function getAllDialogs(): ActiveDataProvider
    {
        $query = SupportDialog::find()
            ->alias('d')
            ->addSelect(['d.*', 'SUM(IF(m.`status`=0 AND (m.user_id is NOT NULL), 1, 0)) as not_read', 'MAX(IF(m.`status`=0 AND (m.user_id is NOT NULL), 1, 0)) as not_read_order'])
            ->with('lastMessage')
            ->leftJoin('{{%support_messages}} m', 'm.dialog_id=d.id')
            //->orderBy(['not_read' => SORT_DESC,'d.id' => SORT_DESC])
            ->groupBy('d.id');

        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['date' => SORT_DESC],
                'attributes' => [
                    'date' => [
                        'asc' => ['not_read_order' => SORT_DESC, 'MAX(m.created_at)' => SORT_ASC],
                        'desc' => ['not_read_order' => SORT_DESC, 'MAX(m.created_at)' => SORT_DESC],
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

    public function getDialogMessages(SupportDialog $dialog): ActiveDataProvider
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
        SupportMessage::updateAll(['status' => 1], ['dialog_id' => $dialogId, 'user_id' => $userId]);
    }

    public function save(SupportDialog $dialog): void
    {
        if (!$dialog->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function saveMessage(SupportMessage $message): void
    {
        if (!$message->save()) {
            throw new \RuntimeException('Ошибка записи в базу.');
        }
    }

    public function remove(SupportDialog $dialog): void
    {
        if (!$dialog->delete()) {
            throw new \RuntimeException('Ошибка при удалении из базы.');
        }
    }
}