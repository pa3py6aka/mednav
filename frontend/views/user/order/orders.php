<?php
use yii\helpers\ArrayHelper;
use core\entities\Order\Order;
use yii\helpers\Html;
use core\helpers\PaginationHelper;
use core\helpers\OrderHelper;

/* @var $this yii\web\View */
/* @var $provider \yii\data\ActiveDataProvider */

/* @var $tab string */
/* @var $pagination \yii\data\Pagination|null */

$this->title = 'Личный кабинет | Заказы';
$tab =  ArrayHelper::getValue($this->params, 'tab', 'active');
$pagination = ArrayHelper::getValue($this->params, 'pagination');

?>
<div class="row">
    <div class="col-md-3">
        <?= $this->render('@frontend/views/user/_left_menu', ['user' => $this->params['user']]) ?>
    </div>

    <div class="col-md-9">
        <?= \frontend\widgets\AccountBreadcrumbs::show(['Заказы']) ?>
        <h1>
            Заказы
            <span class="pull-right">
                <?= PaginationHelper::pageSizeSelector($provider->pagination, PaginationHelper::SITE_SIZES); ?>
            </span>
        </h1>


        <?= \yii\grid\GridView::widget([
            'dataProvider' => $provider,
            'layout' => "{items}\n{summary}\n{pager}",
            'id' => 'grid',
            'columns' => [
                [
                    'label' => 'Создан',
                    'attribute' => 'created_at',
                    'value' => function (Order $order) {
                        return Yii::$app->formatter->asDatetime($order->created_at) . OrderHelper::newLabel($order);
                    },
                    'format' => 'raw',
                ],
                [
                    'label' => '№ заказа',
                    'attribute' => 'id',
                    'value' => function (Order $order) {
                        return Html::a($order->getNumber(), ['view', 'id' => $order->id]);
                    },
                    'format' => 'raw',
                ],
                [
                    'label' => 'Покупатель',
                    'attribute' => 'buyer',
                    'value' => function (Order $order) {
                        return $order->user_id ? $order->user->getVisibleName() : "Посетитель сайта";
                    },
                ],
                [
                    'label' => 'Продавец',
                    'attribute' => 'seller',
                    'value' => function (Order $order) {
                        $sellerName = $order->forCompany->getFullName();
                        return Yii::$app->user->identity->company && Yii::$app->user->identity->company->id !== $order->for_company_id ?
                            Html::a($sellerName, $order->forCompany->getUrl()) :
                            $sellerName;
                    },
                    'format' => 'raw',
                ],
                ['class' => \yii\grid\ActionColumn::class, 'template' => '{view}']
            ],
        ]) ?>
    </div>
</div>
