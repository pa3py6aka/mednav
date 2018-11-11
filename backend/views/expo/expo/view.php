<?php

use core\entities\Expo\Expo;
use core\helpers\HtmlHelper;
use frontend\widgets\PhotosManagerWidget;
use yii\helpers\Html;
use yii\widgets\DetailView;
use core\helpers\StatusHelper;

/* @var $this yii\web\View */
/* @var $model Expo */
/* @var $photosForm \core\forms\manage\PhotosForm */
/* @var $tab string */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Выставки', 'url' => ['active']];
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
                        'name',
                        [
                            'attribute' => 'user_id',
                            'label' => 'Пользователь',
                            'value' => function (Expo $expo) {
                                return $expo->user_id . ' ' . Html::a($expo->user->getUserName(), ['/user/view', 'id' => $expo->user_id]);
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => 'Категория',
                            'value' => function (Expo $expo) {
                                return Html::a($expo->category->name, ['/expo/category/update', 'id' => $expo->category_id]);
                            },
                            'format' => 'raw',
                        ],
                        'title',
                        'meta_description',
                        'intro:html',
                        'full_text:html',
                        'slug',
                        'indirect_links:boolean',
                        [
                            'label' => 'Тэги',
                            'value' => function (Expo $expo) {
                                return $expo->getTagsString();
                            },
                        ],
                        [
                            'attribute' => 'status',
                            'value' => function (Expo $expo) {
                                return StatusHelper::statusBadge($expo->status, $expo->statusName);
                            },
                            'format' => 'raw',
                        ],
                        'show_dates:boolean',
                        'start_date:date',
                        'end_date:date',
                        'city',
                        'created_at:datetime:Добавлена',
                        'updated_at:datetime:Обновлена',
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
