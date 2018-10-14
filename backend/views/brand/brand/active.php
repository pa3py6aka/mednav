<?php

use yii\helpers\Html;
use yii\grid\GridView;
use core\entities\Brand\Brand;
use yii\grid\ActionColumn;
use core\helpers\PaginationHelper;
use yii\grid\CheckboxColumn;
use core\helpers\HtmlHelper;
use core\entities\User\User;
use core\helpers\CategoryHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\BrandSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Бренды';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="board-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Добавить бренд', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        <?= HtmlHelper::actionButtonForSelected('Удалить выбранные', 'remove', 'danger') ?>

        <div class="box-tools">
            <?= PaginationHelper::pageSizeSelector($dataProvider->pagination) ?>
        </div>
    </div>
    <div class="box-body table-responsive">
        <?= $this->render('_tabs', ['tab' => 'active']) ?>

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
                        return Html::a(Html::encode($brand->name), ['/brand/brand/view', 'id' => $brand->id]);
                    },
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
                [
                    'attribute' => 'userType',
                    'label' => 'Профиль',
                    'value' => function(Brand $brand) {
                        return $brand->user->typeName;
                    },
                    'filter' => User::getTypesArray(),
                ],
                [
                    'attribute' => 'category_id',
                    'value' => function (Brand $brand) {
                        return Html::a($brand->category->name, ['/brand/category/update', 'id' => $brand->category_id], [
                            'data-toggle' => 'tooltip',
                            'title' => CategoryHelper::categoryParentsString($brand->category),
                        ]);
                    },
                    'format' => 'raw',
                ],
                'created_at:datetime:Добавлен',
                ['class' => ActionColumn::class, 'template' => '{update} {delete}'],
            ],
        ]); ?>
    </div>
    <div class="box-footer">
        <?= HtmlHelper::actionButtonForSelected('Удалить выбранные', 'remove', 'danger') ?>
    </div>
</div>
