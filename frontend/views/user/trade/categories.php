<?php

use core\entities\Trade\TradeUserCategory;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $provider \yii\data\ActiveDataProvider */

$this->params['tab'] = 'categories';
$this->params['pagination'] = $provider->pagination;

?>
<?= \yii\grid\GridView::widget([
    'dataProvider' => $provider,
    'layout' => "{items}\n{summary}\n{pager}",
    'columns' => [
        [
            'attribute' => 'name',
            'value' => function (TradeUserCategory $category) {
                return Html::a($category->name, ['category', 'id' => $category->id]);
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'category_id',
            'value' => function (TradeUserCategory $category) {
                return $category->category->name;
            },
        ],
        [
            'attribute' => 'uom_id',
            'value' => function (TradeUserCategory $category) {
                return $category->uom->name;
            },
        ],
        [
            'attribute' => 'currency_id',
            'value' => function (TradeUserCategory $category) {
                return $category->currency->name;
            },
        ],
        'wholesale:boolean',
        ['class' => \yii\grid\ActionColumn::class, 'template' => '{update} / {delete}', 'buttons' => [
            'update' => function ($url, TradeUserCategory $category, $key) {
                return Html::a('Редактировать', ['category-update', 'id' => $category->id]);
            },
            'delete' => function ($url, TradeUserCategory $category, $key) {
                return Html::a('Удалить', ['category-remove', 'id' => $category->id], ['data-method' => 'post', 'data-confirm' => 'Удалить данную категорию вместе со всеми привязанными к ней товарами?']);
            }
        ]],
    ],
]) ?>

