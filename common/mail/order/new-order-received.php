<?php
use yii\helpers\Html;
use core\components\Cart\Cart;

/* @var $this yii\web\View */
/* @var $order \core\entities\Order\Order */

$this->title = 'Заказ №: ' . $order->getNumber() . ' от ' . Yii::$app->formatter->asDate($order->created_at);
$ordersLink = Yii::$app->frontendUrlManager->createAbsoluteUrl(['user/order/orders']);
$sum = 0;

?>
<table>
    <tr>
        <th>Наименование:</th>
        <th>Кол-во:</th>
        <th>Цена:</th>
        <th>Сумма:</th>
    </tr>
    <?php foreach ($order->orderItems as $orderItem): ?>
        <tr>
            <td>
                <a href="<?= $orderItem->trade->getUrl(true) ?>"><?= $orderItem->trade->name ?></a>
                <?= $orderItem->trade->code ? '<br>(' . $orderItem->trade->code . ')' : '' ?>
            </td>
            <td>
                <?= $orderItem->amount ?>
            </td>
            <td>
                <?= $orderItem->trade->getPriceString() . '/' . $orderItem->trade->getUomString() ?>
            </td>
            <td>
                <?php $sum = $sum + ($orderItem->trade->price * $orderItem->amount) ?>
                <?= Cart::getItemPrice($orderItem->trade->price, $orderItem->amount) . ' ' . $orderItem->trade->getCurrencyString() ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="4" align="right" style="text-align:right;">
            Итого: <?= \core\helpers\PriceHelper::normalize($sum) . ' ' . $orderItem->trade->getCurrencyString() ?>
        </td>
    </tr>
</table>

<p><b>Комментарий:</b> <?= Html::encode($order->comment) ?></p>
<p>
    <b>Данные покупателя:</b><br>
    <b>"Имя":</b> <?= Html::encode($order->userOrder->user_name) ?><br>
    <b>"Телефон":</b> <?= Html::encode($order->userOrder->user_phone) ?><br>
    <b>"E-mail":</b> <?= Html::encode($order->userOrder->user_email) ?><br>
</p>
<p>----</p>
<p>
    Все заказы <?= $order->forCompany->getFullName() ?> в разделе <a href="<?= $ordersLink ?>">"Заказы"</a> личного кабинета.
</p>