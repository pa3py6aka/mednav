<?php

use yii\helpers\Html;
use yii\grid\GridView;
use core\entities\Order\Order;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index box box-primary">
    <!--<div class="box-header with-border">
        <?= Html::a('Create Order', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>-->
    <div class="box-body table-responsive">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                [
                    'label' => 'Создан',
                    'attribute' => 'created_at',
                    'value' => function (Order $order) {
                        return Yii::$app->formatter->asDatetime($order->created_at);
                    },
                    'format' => 'raw',
                    'filter' => false,
                ],
                [
                    'label' => '№ заказа',
                    'attribute' => 'id',
                    'value' => function (Order $order) {
                        return Html::a($order->id, ['view', 'id' => $order->id]);
                    },
                    'format' => 'raw',
                ],
                [
                    'label' => 'Покупатель',
                    'attribute' => 'buyer',
                    'value' => function (Order $order) {
                        return $order->user_id ? ($order->user->isCompany() && $order->user->isCompanyActive() ? $order->user->company->id : $order->user_id) . ' ' . Html::a(
                                $order->user->getVisibleName(),
                                $order->user->isCompany() && $order->user->isCompanyActive() ? ['/company/company/view', 'id' => $order->user->company->id] : ['/user/view', 'id' => $order->user->id]
                        ) : "Посетитель сайта";
                    },
                    'format' => 'raw',
                    'filterInputOptions' => ['placeholder' => 'ID пользователя или компании', 'class' => 'form-control']
                ],
                [
                    'label' => 'Продавец',
                    'attribute' => 'seller',
                    'value' => function (Order $order) {
                        $sellerName = $order->forCompany->getFullName();
                        return $order->forCompany->id . ' ' . Html::a($sellerName, ['/company/company/view', 'id' => $order->forCompany->id]);
                    },
                    'format' => 'raw',
                    'filterInputOptions' => ['placeholder' => 'ID компании', 'class' => 'form-control']
                ],
                ['class' => \yii\grid\ActionColumn::class, 'template' => '{view}']
            ],
        ]); ?>
    </div>
</div>
