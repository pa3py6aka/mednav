<?php

use core\entities\Trade\TradeUserCategory;
use yii\helpers\Html;
use core\entities\Trade\Trade;

/* @var $this yii\web\View */
/* @var $provider \yii\data\ActiveDataProvider */

$this->params['tab'] = 'active';
$this->params['pagination'] = $provider->pagination;

?>
<?= \yii\grid\GridView::widget([
    'dataProvider' => $provider,
    'layout' => "{items}\n{summary}\n{pager}",
    'columns' => [
        [
            'attribute' => 'name',
            'value' => function (TradeUserCategory $category) {
                return Html::a($category->name, ['category', 'id' => $category->id, 'status' => Trade::STATUS_ACTIVE]);
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'count',
            'label' => 'Товаров',
            'value' => function (TradeUserCategory $category) {
                return count($category->activeTrades);
            },
        ],
        [
            'attribute' => 'currency_id',
            'value' => function (TradeUserCategory $category) {
                return $category->currency->sign;
            },
        ],
        [
            'attribute' => 'uom_id',
            'value' => function (TradeUserCategory $category) {
                return $category->uom->sign;
            },
        ],
        [
            'attribute' => 'category_id',
            'value' => function (TradeUserCategory $category) {
                return $category->category->name;
            },
        ],
        ['class' => \yii\grid\ActionColumn::class, 'template' => '{update} {delete}', 'buttons' => [
            'update' => function ($url, TradeUserCategory $category, $key) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['category-update', 'id' => $category->id]);
            },
            'delete' => function ($url, TradeUserCategory $category, $key) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['category-remove', 'id' => $category->id], ['data-method' => 'post', 'data-confirm' => 'Удалить данную категорию вместе со всеми привязанными к ней товарами?']);
            }
        ]],
    ],
]) ?>


<?php /*= \yii\grid\GridView::widget([
    'dataProvider' => $provider,
    'layout' => "{items}\n{summary}\n{pager}",
    'id' => 'grid',
    'columns' => [
        ['class' => \yii\grid\CheckboxColumn::class],
        ['class' => \yii\grid\SerialColumn::class],
        [
            'attribute' => 'name',
            'value' => function (Trade $trade) {
                return Html::a(Html::encode($trade->name), $trade->getUrl());
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'stock',
            'value' => function (Trade $trade) {
                return $trade->getStockString();
            },
        ],
        [
            'attribute' => 'currency_id',
            'value' => function (Trade $trade) {
                return $trade->userCategory->currency->sign;
            },
        ],
        'views',
        [
            'attribute' => 'category_id',
            'value' => function (Trade $trade) {
                return Html::encode($trade->category->name);
            },
            'format' => 'raw',
        ],
        ['class' => \yii\grid\ActionColumn::class, 'template' => '{update} / {delete}', 'buttons' => [
            'update' => function ($url, Trade $trade, $key) {
                return Html::a('Редактировать', $url);
            },
            'delete' => function ($url, Trade $trade, $key) {
                return Html::a('Удалить', $url, ['data-method' => 'post', 'data-confirm' => 'Удалить данный товар?']);
            }
        ]],
    ],
])*/ ?>

