<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use core\entities\Order\OrderItem;
use core\components\Cart\Cart;
use core\entities\Order\Order;

/* @var $this yii\web\View */
/* @var $fullOrder core\entities\Order\UserOrder */

/* @var $order Order */

$this->title = 'Заказ № ' . $fullOrder->id;
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$itemsSum = 0;
?>
<?php //= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
<?= Html::a('Удалить', ['delete', 'id' => $fullOrder->id], [
    'class' => 'btn btn-danger btn-flat',
    'data' => [
        'confirm' => 'Вы уверены что хотите удалить данный заказ?',
        'method' => 'post',
    ],
]) ?>
<br><br>
<div class="box box-primary">
    <div class="box-body table-responsive">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <?php foreach ($fullOrder->getOrders()->with('orderItems')->all() as $order): ?>
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="order-<?= $order->id ?>">
                        <h4 class="panel-title">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-<?= $order->id ?>" aria-expanded="false" aria-controls="collapse-<?= $order->id ?>">
                                <?= $order->getNumber() ?>
                            </a>
                        </h4>
                    </div>
                    <div id="collapse-<?= $order->id ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-<?= $order->id ?>">
                        <div class="panel-body" style="padding:0">
                            <?= DetailView::widget([
                                'model' => $order,
                                'attributes' => [
                                    [
                                        'label' => 'Продавец',
                                        'value' => function (Order $order) {
                                            return $order->forCompany->id . ' ' . Html::a($order->forCompany->getFullName(), ['/company/company/view', 'id' => $order->forCompany->id]);
                                        },
                                        'format' => 'raw',
                                    ],
                                    [
                                        'label' => 'Покупатель',
                                        'value' => function (Order $order) {
                                            return $order->user_id ? ($order->user->isCompany() && $order->user->isCompanyActive() ? $order->user->company->id : $order->user_id) . ' ' . Html::a(
                                                    $order->user->getVisibleName(),
                                                    $order->user->isCompany() && $order->user->isCompanyActive() ? ['/company/company/view', 'id' => $order->user->company->id] : ['/user/view', 'id' => $order->user->id]
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
                                ],
                            ]) ?>
                            <strong style="margin:-11px 11px 8px;display:block;">Товары:</strong>
                            <table class="table table-striped">
                                <tr>
                                    <th>Наименование</th>
                                    <th>Кол-во</th>
                                    <th>Цена</th>
                                    <th>Сумма</th>
                                </tr>
                                <?php foreach ($order->orderItems as $orderItem): ?>
                                    <tr>
                                        <td>
                                            <?= Html::a(Html::encode($orderItem->trade->name), ['/trade/trade/view', 'id' => $orderItem->trade_id]) .
                                            "<br>Артикул: " . Html::encode($orderItem->trade->code); ?>
                                        </td>
                                        <td>
                                            <?= $orderItem->amount . " " . $orderItem->trade->getUomString() ?>
                                        </td>
                                        <td>
                                            <?= $orderItem->trade->getPriceString() . "/" . $orderItem->trade->getUomString() ?>
                                        </td>
                                        <td>
                                            <?= Cart::getItemPrice($orderItem->trade->price, $orderItem->amount) . " " . $orderItem->trade->getCurrencyString(); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="box-header">
            <h3 class="box-title">Данные о покупателе</h3>
        </div>
        <div class="box-body">
            <?php
            $rows = [];
            $rows[] = '<tr><td>ФИО/Компания</td><td>' . Html::encode($fullOrder->user_name) . '</td></tr>';
            $rows[] = '<tr><td>Телефон</td><td>' . Html::encode($fullOrder->user_phone) . '</td></tr>';
            $rows[] = '<tr><td>E-mail</td><td>' . Html::encode($fullOrder->user_email) . '</td></tr>';
            $rows[] = $fullOrder->address ? '<tr><td>Адрес</td><td>' . Html::encode($fullOrder->address) . '</td></tr>' : '';
            echo Html::tag('table', implode("\n", $rows), [
                'class' => 'table table-bordered no-padding',
                'style' => 'margin-bottom:0;'
            ]);
            ?>
        </div>
        <?php /*= \yii\grid\GridView::widget([
            'dataProvider' => $orderItemsProvider,
            'layout' => "{items}",
            'id' => 'grid',
            'columns' => [
                [
                    'label' => 'Наименование',
                    'value' => function (OrderItem $orderItem) {
                        return Html::a(Html::encode($orderItem->trade->name), ['/trade/trade/view', 'id' => $orderItem->trade_id]) .
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
        <b class="pull-right">Итого: <?= \core\helpers\PriceHelper::normalize($itemsSum) . " " . current($orderItemsProvider->models)->trade->getCurrencyString() ?></b> */ ?>
    </div>
</div>

<?php /*<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Данные заказа</h3>
    </div>
    <div class="box-body table-responsive">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label' => 'Продавец',
                    'value' => function (Order $order) {
                        return $order->forCompany->id . ' ' . Html::a($order->forCompany->getFullName(), ['/company/company/view', 'id' => $order->forCompany->id]);
                    },
                    'format' => 'raw',
                ],
                [
                    'label' => 'Покупатель',
                    'value' => function (Order $order) {
                        return $order->user_id ? ($order->user->isCompany() && $order->user->isCompanyActive() ? $order->user->company->id : $order->user_id) . ' ' . Html::a(
                                $order->user->getVisibleName(),
                                $order->user->isCompany() && $order->user->isCompanyActive() ? ['/company/company/view', 'id' => $order->user->company->id] : ['/user/view', 'id' => $order->user->id]
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
</div>*/ ?>

