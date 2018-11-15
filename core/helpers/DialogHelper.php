<?php

namespace core\helpers;


use core\entities\Dialog\Dialog;
use core\entities\Dialog\Message;
use core\entities\SupportDialog\SupportDialog;
use core\entities\SupportDialog\SupportMessage;
use core\entities\User\User;
use yii\helpers\Html;

class DialogHelper
{
    public static function getNewMessagesCount(User $user): string
    {
        $count = Message::find()->alias('m')
            ->leftJoin(Dialog::tableName(). ' d', 'm.dialog_id=d.id')
            ->where(['or', ['d.user_to' => $user->id], ['d.user_from' => $user->id]])
            ->andWhere(['m.status' => 0])
            ->andWhere([
                'or',
                ['m.user_id' => null],
                ['<>', 'm.user_id', $user->id]
            ])
            //->andWhere(['not', ['m.user_id' => $user->id]])
            ->count();
        if ($count) {
            return ' <span class="label label-danger label-as-badge">' . $count . '</span>';
        }
        return '';
    }

    public static function getUserName(Message $message): string
    {
        return $message->isMy() ? "Я"
            : ($message->user_id ?
                Html::a($message->user->getVisibleName(), $message->user->getUrl(), ['target' => '_blank'])
                : "Посетитель сайта");
    }

    public static function getSupportNewMessagesCount(User $user = null): string
    {
        $query = SupportMessage::find()->alias('m')
            ->leftJoin(SupportDialog::tableName(). ' d', 'm.dialog_id=d.id')
            ->andWhere(['m.status' => 0]);

        if ($user) {
            $query->andWhere(['d.user_id' => $user->id])
                ->andWhere(['m.user_id' => null]);
        } else {
            $query->andWhere(['not', ['m.user_id' => null]]);
        }


        $count = $query->count();
        if ($count) {
            return ' <span class="label label-danger label-as-badge">' . $count . '</span>';
        }
        return '';
    }

    public static function getSupportUserName(SupportMessage $message): string
    {
        return $message->isMy() ? "Я"
            : ($message->user_id ?
                Html::a($message->user->getVisibleName(), $message->user->getUrl(), ['target' => '_blank'])
                : "Служба поддержки");
    }
}