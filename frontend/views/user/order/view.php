<?php
use core\entities\Order\Order;
use core\entities\Order\OrderItem;
use yii\helpers\Html;
use core\components\Cart\Cart;

/* @var $this yii\web\View */
/* @var $order Order */
/* @var $orderItemsProvider \yii\data\ActiveDataProvider */

$this->title = 'Личный кабинет | Заказ № ' . $order->getNumber();
$itemsSum = 0;

?>
<div class="row">
    <div class="col-md-3">
        <?= $this->render('@frontend/views/user/_left_menu', ['user' => $this->params['user']]) ?>
    </div>

    <div class="col-md-9">
        <?= \frontend\widgets\AccountBreadcrumbs::show([
            ['label' => 'Заказы', 'url' => ['/user/order/orders']],
            'Заказ № ' . $order->getNumber()
        ]) ?>
        <h1>Заказ № <?= $order->getNumber() ?></h1>

        <div class="panel panel-default">
            <div class="panel-heading">Товары</div>
            <div class="panel-body">
                <?php if (!$orderItemsProvider->models):
                    echo '<b>Товар больше не продается</b><br>';
                else: ?>
                <?= \yii\grid\GridView::widget([
                    'dataProvider' => $orderItemsProvider,
                    'layout' => '{items}',
                    'id' => 'grid',
                    'showOnEmpty' => false,
                    'columns' => [
                        [
                            'label' => 'Наименование',
                            'value' => function (OrderItem $orderItem) {
                                if (!$orderItem->trade) {
                                    return 'Товар больше не продается';
                                }
                                return Html::a(Html::encode($orderItem->trade->name), $orderItem->trade->getUrl()) .
                                    "<br>Артикул: " . Html::encode($orderItem->trade->code);
                            },
                            'format' => 'raw',
                        ],
                        'amount',
                        [
                            'label' => 'Цена',
                            'value' => function (OrderItem $orderItem) {
                                if (!$orderItem->trade) {
                                    return '-';
                                }
                                return $orderItem->trade->getPriceString() . "/" . $orderItem->trade->getUomString();
                            },
                        ],
                        [
                            'label' => 'Сумма',
                            'value' => function (OrderItem $orderItem) use (&$itemsSum) {
                                $itemsSum += $orderItem->trade->price * $orderItem->amount;
                                return Cart::getItemPrice($orderItem->trade->price, $orderItem->amount) . " " . $orderItem->trade->getCurrencyString();
                            },
                        ],
                    ],
                ]) ?>
                <?php endif; ?>
                <b class="pull-right">
                    Итого:
                    <?= current($orderItemsProvider->models)
                        ? \core\helpers\PriceHelper::normalize($itemsSum) . ' ' . current($orderItemsProvider->models)->trade->getCurrencyString()
                        : '-' ?>
                </b>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Данные заказа</div>
            <div class="panel-body">
                <?= \yii\widgets\DetailView::widget([
                    'model' => $order,
                    'attributes' => [
                        [
                            'label' => 'Продавец',
                            'value' => function (Order $order) {
                                return Html::a($order->forCompany->getFullName(), $order->forCompany->getUrl());
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => 'Покупатель',
                            'value' => function (Order $order) {
                                if ($order->user_id) {
                                    return $order->user->isCompany() && $order->user->isCompanyActive() ?
                                        Html::a($order->user->company->getFullName(), $order->user->company->getUrl())
                                        : $order->user->getVisibleName();
                                }
                                return 'Посетитель сайта';
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => 'Доставка',
                            'value' => function (Order $order) {
                                return $order->delivery_id ? $order->delivery->name : null;
                            },
                        ],
                        'comment:ntext',
                        [
                            'label' => 'Данные покупателя',
                            'value' => function (Order $order) {
                                $rows = [];
                                $rows[] = '<tr><td>ФИО/Компания</td><td>' . Html::encode($order->userOrder->user_name) . '</td></tr>';
                                $rows[] = '<tr><td>Телефон</td><td>' . Html::encode($order->userOrder->user_phone) . '</td></tr>';
                                $rows[] = '<tr><td>E-mail</td><td>' . Html::encode($order->userOrder->user_email) . '</td></tr>';
                                $rows[] = $order->userOrder->address ? '<tr><td>Адрес</td><td>' . Html::encode($order->userOrder->address) . '</td></tr>' : '';
                                return Html::tag('table', implode("\n", $rows), [
                                    'class' => 'table table-bordered no-padding',
                                    'style' => 'margin-bottom:0;'
                                ]);
                            },
                            'format' => 'raw',
                            'contentOptions' => ['class' => 'no-padding'],
                        ],

                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
