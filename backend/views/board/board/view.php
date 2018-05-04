<?php

use core\entities\Board\Board;
use core\helpers\AdminLteHelper;
use core\helpers\BoardHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model core\entities\Board\Board */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Доска объявлений', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="board-view box box-primary">
    <div class="box-header">
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= AdminLteHelper::softDeleteButton($model->id) ?>


    </div>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                [
                    'attribute' => 'author_id',
                    'value' => function (Board $board) {
                        return Html::a($board->author->email, ['/user/view', 'id' => $board->author_id]);
                    },
                    'format' => 'raw',
                ],
                'name',
                'slug',
                [
                    'attribute' => 'category_id',
                    'value' => function (Board $board) {
                        return Html::a($board->category->name, ['/board/category/update', 'id' => $board->category_id]);
                    },
                    'format' => 'raw',
                ],
                'title',
                'description:ntext',
                'keywords:ntext',
                'note',
                'priceString',
                //'currency',
                //'price_from:boolean',
                'full_text:html',
                [
                    'label' => 'Параметры',
                    'value' => function (Board $board) {
                        $rows = [];
                        /* @var  $assignment \core\entities\Board\BoardParameterAssignment */
                        foreach ($board->getBoardParameters()->with('parameter', 'option')->all() as $assignment) {
                            $rows[] = '<tr><th>' . $assignment->parameter->name . '</th><td>' . $assignment->getValueByType() . '</td></tr>';
                        }
                        return Html::tag('table', implode("\n", $rows), [
                            'class' => 'table table-bordered no-padding',
                            'style' => 'margin-bottom:0;'
                        ]);
                    },
                    'format' => 'raw',
                    'contentOptions' => ['class' => 'no-padding'],
                ],
                [
                    'attribute' => 'term_id',
                    'value' => function (Board $board) {
                        return $board->term->daysHuman;
                    },
                ],
                [
                    'attribute' => 'geo_id',
                    'value' => function (Board $board) {
                        return Html::a($board->geo->name, ['/geo/update', 'id' => $board->geo_id]);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'status',
                    'value' => function (Board $board) {
                        return BoardHelper::statusBadge($board->status, $board->statusName);
                    },
                    'format' => 'raw',
                ],
                'active_until:datetime',
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>
    </div>
</div>
