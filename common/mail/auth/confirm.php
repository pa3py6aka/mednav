<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \core\entities\User\User */

$this->title = 'Подтверждение регистрации';
$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/signup/confirm', 'token' => $user->email_confirm_token]);
?>
<p>Перейдите по этой ссылке для завершения регистрации:</p>
<p><?= Html::a(Html::encode($confirmLink), $confirmLink) ?></p>