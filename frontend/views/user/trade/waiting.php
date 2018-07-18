<?php
use core\entities\Trade\Trade;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $provider \yii\data\ActiveDataProvider */

$this->params['tab'] = 'waiting';
$this->params['pagination'] = $provider->pagination;

?>

<?= \yii\grid\GridView::widget([
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
        'created_at:date',
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
]) ?>

