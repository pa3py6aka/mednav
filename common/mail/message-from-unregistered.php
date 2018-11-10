<?php

/* @var $message \core\entities\Dialog\Message */
/* @var $page string */

?>
<p><strong>Дата:</strong> <?= Yii::$app->formatter->asDate($message->created_at) ?></p>
<p><strong>Тема:</strong> <?= $message->dialog->subject ?></p>
<p><strong>Сообщение:</strong> <?= Yii::$app->formatter->asNtext($message->text) ?></p>
<p><strong>От:</strong> <?= $message->dialog->name ?></p>
<p><strong>E-mail:</strong> <?= $message->dialog->email ?></p>
<p><strong>Телефон:</strong> <?= $message->dialog->phone ?></p>
<p>---</p>
<p>
    <span style="font-style:italic;">Данное сообщение отправлено посетителем на странице <a href="<?= $page ?>"><?= $page ?></a></span>
</p>