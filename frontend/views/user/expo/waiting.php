<?php

use yii\helpers\Html;
use core\entities\Expo\Expo;

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
        [
            'attribute' => 'name',
            'value' => function (Expo $expo) {
                return Html::a(Html::encode($expo->name), $expo->getUrl(), ['target' => '_blank']);
            },
            'format' => 'raw',
        ],
        'created_at:date',
        ['class' => \yii\grid\ActionColumn::class, 'template' => '{update} / {delete}', 'buttons' => [
            'update' => function ($url, Expo $expo, $key) {
                return Html::a('Редактировать', ['update', 'id' => $expo->id]);
            },
            'delete' => function ($url, Expo $expo, $key) {
                return Html::a('Удалить', ['remove', 'id' => $expo->id], ['data-method' => 'post', 'data-confirm' => 'Удалить данную выставку?']);
            }
        ]],
    ],
]) ?>

