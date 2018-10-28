<?php

use yii\helpers\Html;
use yii\grid\GridView;
use core\entities\Order\UserOrder;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\FullOrderSearch */
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
                    'value' => function (UserOrder $fullOrder) {
                        return Yii::$app->formatter->asDatetime($fullOrder->created_at);
                    },
                    'format' => 'raw',
                    'filter' => false,
                ],
                [
                    'label' => '№ заказа',
                    'attribute' => 'id',
                    'value' => function (UserOrder $fullOrder) {
                        return Html::a($fullOrder->id, ['view', 'id' => $fullOrder->id]);
                    },
                    'format' => 'raw',
                ],
                [
                    'label' => 'Покупатель',
                    'attribute' => 'buyer',
                    'value' => function (UserOrder $fullOrder) {
                        return $fullOrder->user_id ? ($fullOrder->user->isCompany() && $fullOrder->user->isCompanyActive() ? $fullOrder->user->company->id : $fullOrder->user_id) . ' ' . Html::a(
                                $fullOrder->user->getVisibleName(),
                                $fullOrder->user->isCompany() && $fullOrder->user->isCompanyActive() ? ['/company/company/view', 'id' => $fullOrder->user->company->id] : ['/user/view', 'id' => $fullOrder->user->id]
                        ) : "Посетитель сайта";
                    },
                    'format' => 'raw',
                    'filterInputOptions' => ['placeholder' => 'ID пользователя или компании', 'class' => 'form-control']
                ],
                /*[
                    'label' => 'Продавец',
                    'attribute' => 'seller',
                    'value' => function (UserOrder $order) {
                        $sellerName = $order->forCompany->getFullName();
                        return $order->forCompany->id . ' ' . Html::a($sellerName, ['/company/company/view', 'id' => $order->forCompany->id]);
                    },
                    'format' => 'raw',
                    'filterInputOptions' => ['placeholder' => 'ID компании', 'class' => 'form-control']
                ],*/
                ['class' => \yii\grid\ActionColumn::class, 'template' => '{view}']
            ],
        ]); ?>
    </div>
</div>
