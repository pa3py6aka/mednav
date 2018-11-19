<?php

use yii\helpers\Html;

/* @var $message \core\entities\Dialog\Message */

?>
<p><strong>Дата:</strong> <?= Yii::$app->formatter->asDatetime($message->created_at) ?></p>
<p><strong>Тема:</strong> <?= Html::encode($message->dialog->subject) ?></p>
<p><strong>Сообщение:</strong> <?= Yii::$app->formatter->asNtext($message->text) ?></p>
<p><strong>От:</strong> <?= $message->user_id ?
        '<a href="' . $message->user->getUrl(true) . '">' . $message->user->getVisibleName() . '</a>'
        : $message->dialog->name ?></p>
<p><strong>Email:</strong> <?= $message->user_id ?
        '<a href="mailto:' . $message->user->getEmail() . '">' . $message->user->getEmail() . '</a>'
        : '<a href="mailto:' . $message->dialog->email . '">' . $message->dialog->email . '</a>' ?></p>
<p><strong>Телефон:</strong> <?= Html::encode($message->user_id ?
        $message->user->getPhoneString()
        : $message->dialog->phone) ?></p>
<p>---</p>
<span style="font-style:italic;">Все Ваши сообщения Вы можете прочитать в личном кабинете: <a href="<?= Yii::$app->frontendUrlManager->createAbsoluteUrl(['user/message/dialogs']) ?>"><?= Yii::$app->frontendUrlManager->createAbsoluteUrl(['user/message/dialogs']) ?></a></span>