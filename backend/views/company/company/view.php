<?php

use core\entities\Company\Company;
use core\helpers\HtmlHelper;
use frontend\widgets\PhotosManagerWidget;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model Company */
/* @var $photosForm \core\forms\manage\PhotosForm */
/* @var $tab string */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Компании', 'url' => ['active']];
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
                            'label' => 'Администратор',
                            'value' => function (Company $company) {
                                return $company->user_id . ' ' . Html::a($company->user->getUserName(), ['/user/view', 'id' => $company->user_id]);
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => 'Логотип',
                            'value' => function (Company $company) {
                                return Html::img($company->getLogoUrl(true), ['style' => 'max-height:150px;']);
                            },
                            'format' => 'raw',
                        ],
                        'form',
                        'name',
                        'slug',
                        'site',
                        [
                            'attribute' => 'geo_id',
                            'value' => function (Company $company) {
                                return Html::a($company->geo->name, ['/geo/update', 'id' => $company->geo_id]);
                            },
                            'format' => 'raw',
                        ],
                        'address',
                        [
                            'attribute' => 'phones',
                            'value' => function (Company $company) {
                                return Html::ul($company->getPhones());
                            },
                            'format' => 'raw',
                        ],
                        'fax',
                        'email:email',
                        'info:html',
                        'title',
                        'short_description:html',
                        'description:html',
                        [
                            'label' => 'Категории',
                            'value' => function (Company $company) {
                                return Html::ul($company->categories, ['item' => function ($item, $index) {
                                    /* @var $item \core\entities\Company\CompanyCategory */
                                    return '<li>' . Html::a($item->name, ['/company/category/update', 'id' => $item->id]) . '</li>';
                                }]);
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => 'Тэги',
                            'value' => function (Company $company) {
                                $tags = $company->getTags()->select(['name'])->column();
                                return implode(", ", $tags);
                            },
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
