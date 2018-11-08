<?php
use yii\helpers\Html;
use core\components\Cart\Cart;

/* @var $this yii\web\View */
/* @var $userOrder \core\entities\Order\UserOrder */

$this->title = '';
$ordersLink = Yii::$app->frontendUrlManager->createAbsoluteUrl(['user/order/orders']);
$sum = 0;

?>
<?php foreach ($userOrder->orders as $order): ?>
    <p><b>Заказ №":</b> <?= $order->getNumber() ?> от <?= $order->forCompany->getFullName() ?>, <?= Yii::$app->formatter->asDate($order->created_at) ?></p>
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
                    <?= $orderItem->trade->name ?>
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
    <hr>
    <?php $sum = 0; ?>
<?php endforeach; ?>

<p>
    <b>Данные покупателя:</b><br>
    <b>"Имя":</b> <?= Html::encode($userOrder->user_name) ?><br>
    <b>"Телефон":</b> <?= Html::encode($userOrder->user_phone) ?><br>
    <b>"E-mail":</b> <?= Html::encode($userOrder->user_email) ?><br>
</p>
<p>----</p>
<p>
    Все заказы в разделе <a href="<?= $ordersLink ?>">"Заказы"</a> личного кабинета.
</p>