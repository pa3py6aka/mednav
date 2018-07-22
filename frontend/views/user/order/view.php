<?php
use core\entities\Order\Order;
use core\entities\Order\OrderItem;
use yii\helpers\Html;
use core\components\Cart\Cart;

/* @var $this yii\web\View */
/* @var $order Order */
/* @var $orderItemsProvider \yii\data\ActiveDataProvider */

$this->title = 'Личный кабинет | Заказ № ' . $order->id;
$itemsSum = 0;

?>
<div class="row">
    <div class="col-md-3">
        <?= $this->render('@frontend/views/user/_left_menu', ['user' => $this->params['user']]) ?>
    </div>

    <div class="col-md-9">
        <?= \frontend\widgets\AccountBreadcrumbs::show(['Заказ № ' . $order->id]) ?>
        <h1>Заказ № <?= $order->id ?></h1>

        <div class="panel panel-default">
            <div class="panel-heading">Товары</div>
            <div class="panel-body">
                <?= \yii\grid\GridView::widget([
                    'dataProvider' => $orderItemsProvider,
                    'layout' => "{items}",
                    'id' => 'grid',
                    'columns' => [
                        [
                            'label' => 'Наименование',
                            'value' => function (OrderItem $orderItem) {
                                return Html::a(Html::encode($orderItem->trade->name), $orderItem->trade->getUrl()) .
                                    "<br>Артикул: " . Html::encode($orderItem->trade->code);
                            },
                            'format' => 'raw',
                        ],
                        'amount',
                        [
                            'label' => 'Цена',
                            'value' => function (OrderItem $orderItem) {
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
                <b class="pull-right">Итого: <?= \core\helpers\PriceHelper::normalize($itemsSum) . " " . current($orderItemsProvider->models)->trade->getCurrencyString() ?></b>
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
                                return $order->user_id && $order->user->isCompany() && $order->user->isCompanyActive() ? Html::a(
                                    $order->user->getVisibleName(),
                                    $order->user->company->getFullName()
                                ) : "Посетитель сайта";
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
                                $rows[] = '<tr><td>ФИО/Компания</td><td>' . Html::encode($order->user_name) . '</td></tr>';
                                $rows[] = '<tr><td>Телефон</td><td>' . Html::encode($order->user_phone) . '</td></tr>';
                                $rows[] = '<tr><td>E-mail</td><td>' . Html::encode($order->user_email) . '</td></tr>';
                                $rows[] = $order->address ? '<tr><td>Адрес</td><td>' . Html::encode($order->address) . '</td></tr>' : '';
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
