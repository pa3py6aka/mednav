<?php

use core\entities\Trade\Trade;
use core\helpers\HtmlHelper;
use core\helpers\BoardHelper;
use core\helpers\StatusHelper;
use frontend\widgets\PhotosManagerWidget;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model Trade */
/* @var $photosForm \core\forms\manage\PhotosForm */
/* @var $tab string */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Каталог товаров', 'url' => ['active']];
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
                            'attribute' => 'user_id',
                            'value' => function (Trade $trade) {
                                return Html::a($trade->user->getVisibleName(), ['/user/view', 'id' => $trade->user_id]);
                            },
                            'format' => 'raw',
                        ],
                        'name:ntext',
                        [
                            'attribute' => 'category_id',
                            'value' => function (Trade $trade) {
                                return Html::a($trade->category->name, ['/trade/category/update', 'id' => $trade->category_id]);
                            },
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'user_category_id',
                            'value' => function (Trade $trade) {
                                return Html::encode($trade->userCategory->name);
                            },
                        ],
                        [
                            'attribute' => 'geo_id',
                            'value' => function (Trade $trade) {
                                return Html::a($trade->geo->name, ['/geo/update', 'id' => $trade->geo_id]);
                            },
                            'format' => 'raw',
                        ],
                        'code',
                        'priceString',
                        [
                            'attribute' => 'wholesale_prices',
                            'value' => function (Trade $trade) {
                                $rows = [];
                                foreach ($trade->getWholesales() as $wholesale) {
                                    $rows[] = '<tr><td>От ' . $wholesale['from'] . '</td><td>' . $wholesale['price'] . ' ' . $trade->userCategory->currency->sign . '</td></tr>';
                                }
                                return Html::tag('table', implode("\n", $rows), [
                                    'class' => 'table table-bordered no-padding',
                                    'style' => 'margin-bottom:0;'
                                ]);
                            },
                            'format' => 'raw',
                            'contentOptions' => ['class' => 'no-padding'],
                        ],
                        'stock:boolean',
                        'note:ntext',
                        'description:html',

                        'meta_title',
                        'meta_description:ntext',
                        'meta_keywords',
                        'slug',
                        [
                            'label' => 'Тэги',
                            'value' => function (Trade $trade) {
                                $tags = $trade->getTags()->select(['name'])->column();
                                return implode(", ", $tags);
                            },
                        ],
                        [
                            'attribute' => 'status',
                            'value' => function (Trade $trade) {
                                return StatusHelper::statusBadge($trade->status, $trade->statusName);
                            },
                            'format' => 'raw',
                        ],
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
