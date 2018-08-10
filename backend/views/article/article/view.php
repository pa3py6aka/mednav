<?php

use core\entities\Article\Article;
use core\helpers\HtmlHelper;
use frontend\widgets\PhotosManagerWidget;
use yii\helpers\Html;
use yii\widgets\DetailView;
use core\helpers\StatusHelper;

/* @var $this yii\web\View */
/* @var $model Article */
/* @var $photosForm \core\forms\manage\PhotosForm */
/* @var $tab string */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['active']];
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
                            'value' => function (Article $article) {
                                return $article->user_id . ' ' . Html::a($article->user->getUserName(), ['/user/view', 'id' => $article->user_id]);
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => 'Категория',
                            'value' => function (Article $article) {
                                return Html::a($article->category->name, ['/article/category/update', 'id' => $article->category_id]);
                            },
                            'format' => 'raw',
                        ],
                        'title',
                        'meta_description',
                        'meta_description',
                        'intro:html',
                        'full_text:html',
                        'slug',
                        'indirect_links:boolean',
                        [
                            'label' => 'Тэги',
                            'value' => function (Article $article) {
                                return $article->getTagsString();
                            },
                        ],
                        [
                            'attribute' => 'status',
                            'value' => function (Article $article) {
                                return StatusHelper::statusBadge($article->status, $article->statusName);
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
