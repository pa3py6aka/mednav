<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \core\entities\User\User */

$this->title = 'Профиль активирован';
$link = Yii::$app->frontendUrlManager->createAbsoluteUrl(['auth/auth/login']);
?>
<p>Поздравляем, Ваш профиль прошёл проверку и успешно активирован!</p>
<p>Теперь Вы можете войти на сайт используя свой e-mail и пароль.</p>
<p><?= Html::a('Войти', $link) ?></p>