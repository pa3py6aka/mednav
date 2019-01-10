<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \core\entities\User\User */

$this->title = 'Восстановление доступа';
$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/reset/confirm', 'token' => $user->password_reset_token]);
?>
<p>Перейдите по этой ссылке для сброса пароля:</p>
<p><?= Html::a(Html::encode($confirmLink), $confirmLink) ?></p>