<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $order \core\entities\Order\Order */

$this->title = 'Новый заказ';
$orderLink = Yii::$app->frontendUrlManager->createAbsoluteUrl(['user/order/received', 'id' => $order->id]);
?>
<p>У Вас получен новый заказ на обработку.</p>
<p>Просмотреть детали заказа и управлять статусом заказа Вы можете в своём личном кабинете:</p>
<p><?= Html::a(Html::encode($orderLink), $orderLink) ?></p>