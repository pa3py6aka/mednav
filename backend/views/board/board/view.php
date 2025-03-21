<?php

use core\entities\Board\Board;
use core\helpers\HtmlHelper;
use core\helpers\BoardHelper;
use frontend\widgets\PhotosManagerWidget;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model core\entities\Board\Board */
/* @var $photosForm \core\forms\manage\PhotosForm */
/* @var $tab string */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Доска объявлений', 'url' => ['active']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="board-view box box-primary">
    <div class="box-header">
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= HtmlHelper::softDeleteButton($model->id) ?>
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
                                return Html::a($board->author->getVisibleName(), ['/user/view', 'id' => $board->author_id]);
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
                                return $board->getSafeTerm()->daysHuman;
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
                            'label' => 'Тэги',
                            'value' => function (Board $board) {
                                $tags = $board->getTags()->select(['name'])->column();
                                return implode(", ", $tags);
                            },
                        ],
                        [
                            'attribute' => 'status',
                            'value' => function (Board $board) {
                                $board->updateStatus();
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
                <?= PhotosManagerWidget::widget(['entityId' => $model->id, 'photos' => $model->photos]) ?>
            </div>
        </div>
    </div>
</div>
