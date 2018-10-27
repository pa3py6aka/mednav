<?php

use yii\helpers\Html;
use yii\grid\GridView;
use core\entities\Expo\Expo;
use yii\grid\ActionColumn;
use core\helpers\PaginationHelper;
use yii\grid\CheckboxColumn;
use core\helpers\HtmlHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\ExpoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Выставки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="board-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Добавить выставку', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
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
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => CheckboxColumn::class],
                [
                    'attribute' => 'name',
                    'value' => function (Expo $expo) {
                        return Html::a(Html::encode($expo->name), ['/expo/expo/view', 'id' => $expo->id]);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'user_id',
                    'label' => 'Пользователь',
                    'value' => function (Expo $expo) {
                        return $expo->user_id . ' ' . Html::a($expo->user->getVisibleName(), ['/user/view', 'id' => $expo->user_id]);
                    },
                    'format' => 'raw',
                ],
                ['class' => \core\grid\UserProfileColumn::class],
                ['class' => \core\grid\CategoryColumn::class, 'url' => ['/expo/category/update', 'id' => '{id}']],
                'created_at:datetime:Добавлена',
                ['class' => ActionColumn::class, 'template' => '{update} {delete}'],
            ],
        ]); ?>
    </div>
    <div class="box-footer">
        <?= HtmlHelper::actionButtonForSelected('Удалить выбранные', 'remove', 'danger') ?>
    </div>
</div>
