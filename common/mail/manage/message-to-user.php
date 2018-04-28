<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \core\entities\User\User */
/* @var $message string */

$this->title = 'Сообщение от администратора';
?>
<p><?= nl2br(Html::encode($message)) ?></p>