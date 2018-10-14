<?php

use yii\helpers\Html;
use core\entities\CNews\CNews;

/* @var $this yii\web\View */
/* @var $provider \yii\data\ActiveDataProvider */

$this->params['tab'] = 'active';
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
            'value' => function (CNews $news) {
                return Html::a(Html::encode($news->name), $news->getUrl());
            },
            'format' => 'raw',
        ],
        'created_at:date',
        'views',
        ['class' => \yii\grid\ActionColumn::class, 'template' => '{update} / {delete}', 'buttons' => [
            'update' => function ($url, CNews $news, $key) {
                return Html::a('Редактировать', ['update', 'id' => $news->id]);
            },
            'delete' => function ($url, CNews $news, $key) {
                return Html::a('Удалить', ['remove', 'id' => $news->id], ['data-method' => 'post', 'data-confirm' => 'Удалить данную новость?']);
            }
        ]],
    ],
]) ?>

