<?php
use core\entities\Board\Board;
use yii\helpers\Html;
use core\helpers\BoardHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $provider \yii\data\ActiveDataProvider */
/* @var $toExtend int */

$this->params['tab'] = 'active';
$this->params['pagination'] = $provider->pagination;

?>
<?php if ($toExtend): ?>
    <p>Объявлений на продление: <?= $toExtend ?>. <a href="<?= Url::to(['/user/board/extend', 'all' => 1]) ?>" data-method="post">Продлить все</a></p>
<?php endif; ?>
<?= \yii\grid\GridView::widget([
    'dataProvider' => $provider,
    'layout' => "{items}\n{summary}\n{pager}",
    'id' => 'grid',
    'columns' => [
        ['class' => \yii\grid\CheckboxColumn::class],
        [
            'attribute' => 'name',
            'value' => function (Board $board) {
                return Html::a(Html::encode($board->name), $board->getUrl());
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
            'attribute' => 'active_until',
            'label' => 'Размещено до',
            'value' => function (Board $board) {
                return Yii::$app->formatter->asDatetime($board->active_until) .
                    ($board->hasExtendNotification() ? ' ' . Html::a('Продлить', ['extend', 'id' => $board->id]) : '');
            },
            'format' => 'raw',
        ],
        'views',
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

