<?php

use yii\helpers\Html;
use core\entities\Article\Article;

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
            'value' => function (Article $article) {
                return Html::a(Html::encode($article->name), $article->getUrl());
            },
            'format' => 'raw',
        ],
        'created_at:date',
        'views',
        ['class' => \yii\grid\ActionColumn::class, 'template' => '{update} / {delete}', 'buttons' => [
            'update' => function ($url, Article $article, $key) {
                return Html::a('Редактировать', ['update', 'id' => $article->id]);
            },
            'delete' => function ($url, Article $article, $key) {
                return Html::a('Удалить', ['remove', 'id' => $article->id], ['data-method' => 'post', 'data-confirm' => 'Удалить данную статью?']);
            }
        ]],
    ],
]) ?>

