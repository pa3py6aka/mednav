<?php

use core\entities\Board\Board;
use yii\helpers\Html;
use core\helpers\BoardHelper;

/* @var $this yii\web\View */
/* @var $provider \yii\data\ActiveDataProvider */

$this->params['tab'] = 'waiting';
$this->params['pagination'] = $provider->pagination;

?>
<?= \yii\grid\GridView::widget([
    'dataProvider' => $provider,
    'layout' => "{items}\n{summary}\n{pager}",
    'columns' => [
        [
            'attribute' => 'name',
            'value' => function (Board $board) {
                return Html::a($board->name, $board->getUrl());
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'typeParameter',
            'label' => 'Тип',
            'value' => function (Board $board) {
                return $board->getDefaultType();
            },
        ],
        [
            'attribute' => 'category_id',
            'value' => function (Board $board) {
                return Html::a($board->category->name, BoardHelper::categoryUrl($board->category), [
                    'data-toggle' => 'tooltip',
                    'title' => BoardHelper::categoryParentsString($board->category),
                ]);
            },
            'format' => 'raw',
        ],
        ['class' => \yii\grid\ActionColumn::class, 'template' => '{update} / {delete}', 'buttons' => [
            'update' => function ($url, Board $board, $key) {
                return Html::a('Редактировать', $url);
            },
            'delete' => function ($url, Board $board, $key) {
                return Html::a('Удалить', $url, ['data-method' => 'post', 'data-confirm' => 'Удалить данное объявление?']);
            }
        ]],
    ],
]) ?>

