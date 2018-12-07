<?php

use yii\helpers\Html;

/* @var $message \core\entities\SupportDialog\SupportMessage */

?>
<p><strong>Дата:</strong> <?= Yii::$app->formatter->asDatetime($message->created_at) ?></p>
<p><strong>От кого:</strong> <a href="<?= $message->user->getUrl(true) ?>"><?= $message->user->getVisibleName() ?></a></p>
<p><strong>Тема:</strong> <?= Html::encode($message->dialog->subject) ?></p>
<p><strong>Сообщение:</strong> <?= Yii::$app->formatter->asNtext($message->text) ?></p>
<p>---</p>
<span style="font-style:italic;">Все сообщения Вы можете прочитать в <a href="<?= Yii::$app->backendUrlManager->createAbsoluteUrl(['dialog/dialogs']) ?>">панели администрирования</a></span>