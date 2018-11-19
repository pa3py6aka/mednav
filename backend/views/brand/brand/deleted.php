<?php

use yii\helpers\Html;
use yii\grid\GridView;
use core\grid\ModeratorActionColumn;
use core\entities\Brand\Brand;
use core\helpers\PaginationHelper;
use yii\grid\CheckboxColumn;
use core\helpers\HtmlHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\BrandSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Бренды';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="board-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Добавить бренд', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        <?= HtmlHelper::actionButtonForSelected('Разместить выбранные', 'publish', 'primary') ?>
        <?= HtmlHelper::actionButtonForSelected('Удалить выбранные', 'remove', 'danger') ?>

        <div class="box-tools">
            <?= PaginationHelper::pageSizeSelector($dataProvider->pagination) ?>
        </div>
    </div>
    <div class="box-body table-responsive">
        <?= $this->render('_tabs', ['tab' => 'deleted']) ?>

        <?= GridView::widget([
            'id' => 'grid',
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => CheckboxColumn::class],
                [
                    'attribute' => 'name',
                    'value' => function (Brand $brand) {
                        return '<div style="width:400px;">' . Html::a(Html::encode($brand->name), ['/brand/brand/view', 'id' => $brand->id]) . '</div>';
                    },
                    'contentOptions' => ['style' => 'white-space:normal;'],
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'user_id',
                    'label' => 'Пользователь',
                    'value' => function (Brand $brand) {
                        return $brand->user_id . ' ' . Html::a($brand->user->getVisibleName(), ['/user/view', 'id' => $brand->user_id]);
                    },
                    'format' => 'raw',
                ],
                'created_at:datetime:Добавлен',
                ['class' => ModeratorActionColumn::class],
            ],
        ]); ?>
    </div>
    <div class="box-footer">
        <?= HtmlHelper::actionButtonForSelected('Разместить выбранные', 'publish', 'primary') ?>
        <?= HtmlHelper::actionButtonForSelected('Удалить выбранные', 'remove', 'danger') ?>
    </div>
</div>
