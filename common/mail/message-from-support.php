<?php

use yii\helpers\Html;

/* @var $message \core\entities\SupportDialog\SupportMessage */

?>
<p><strong>Дата:</strong> <?= Yii::$app->formatter->asDatetime($message->created_at) ?></p>
<p><strong>Тема:</strong> <?= Html::encode($message->dialog->subject) ?></p>
<p><strong>Сообщение:</strong> <?= Yii::$app->formatter->asNtext($message->text) ?></p>
<p>---</p>
<span style="font-style:italic;">Все Ваши сообщения Вы можете прочитать в <a href="<?= Yii::$app->frontendUrlManager->createAbsoluteUrl(['user/support/dialogs']) ?>">личном кабинете</a></span>