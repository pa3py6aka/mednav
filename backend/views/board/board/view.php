<?php

use core\entities\Board\Board;
use core\helpers\AdminLteHelper;
use core\helpers\BoardHelper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model core\entities\Board\Board */
/* @var $photosForm \core\forms\manage\Board\BoardPhotosForm */
/* @var $tab string */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Доска объявлений', 'url' => ['active']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="board-view box box-primary">
    <div class="box-header">
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= AdminLteHelper::softDeleteButton($model->id) ?>
    </div>
    <div class="box-body table-responsive">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"<?= $tab == 'main' ? ' class="active"' : '' ?>><a href="#main" role="tab" id="main-tab" data-toggle="tab" aria-controls="main">Основные данные</a></li>
            <li role="presentation"<?= $tab == 'photos' ? ' class="active"' : '' ?>><a href="#photos" role="tab" id="photos-tab" data-toggle="tab" aria-controls="photos">Фотографии</a></li>
        </ul>
        <div class="tab-content">
            <!-- Основные данные -->
            <div class="tab-pane fade<?= $tab == 'main' ? ' active in' : '' ?>" role="tabpanel" id="main" aria-labelledby="main-tab">
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

            <!-- Фотографии -->
            <div class="tab-pane fade<?= $tab == 'photos' ? ' active in' : '' ?>" role="tabpanel" id="photos" aria-labelledby="photos-tab">
                <br>
                <div class="row">
                <?php foreach ($model->photos as $photo): ?>
                    <div class="col-md-2 col-xs-3" style="text-align: center">
                        <div class="btn-group">
                            <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span>', ['move-photo', 'board_id' => $model->id, 'photo_id' => $photo->id, 'direction' => 'up'], [
                                'class' => 'btn btn-default',
                                'data-method' => 'post',
                            ]); ?>
                            <?= Html::a('<span class="glyphicon glyphicon-remove"></span>', ['delete-photo', 'id' => $model->id, 'photo_id' => $photo->id], [
                                'class' => 'btn btn-default',
                                'data-method' => 'post',
                                'data-confirm' => 'Удалить фото?',
                            ]); ?>
                            <?= Html::a('<span class="glyphicon glyphicon-arrow-right"></span>', ['move-photo', 'board_id' => $model->id, 'photo_id' => $photo->id, 'direction' => 'down'], [
                                'class' => 'btn btn-default',
                                'data-method' => 'post',
                            ]); ?>
                        </div>
                        <div>
                            <?= Html::a(
                                Html::img($photo->getUrl('small', true)),
                                $photo->getUrl('max', true),
                                ['class' => 'thumbnail', 'target' => '_blank']
                            ) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
                <?php $form = ActiveForm::begin([
                    'options' => ['enctype'=>'multipart/form-data'],
                ]); ?>

                <?= $form->field($photosForm, 'files[]')->label(false)->widget(\kartik\file\FileInput::class, [
                    'options' => [
                        'accept' => 'image/*',
                        'multiple' => true,
                    ]
                ]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Загрузить', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
