<?php

use yii\helpers\Html;
use yii\grid\GridView;
use core\entities\Trade\Trade;
use yii\helpers\ArrayHelper;
use core\helpers\PaginationHelper;
use yii\grid\CheckboxColumn;
use core\entities\User\User;
use core\helpers\CategoryHelper;
use core\helpers\HtmlHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\TradeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товары';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="board-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Добавить товар', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        <?= HtmlHelper::actionButtonForSelected('Разместить выбранные', 'publish', 'primary') ?>
        <?= HtmlHelper::actionButtonForSelected('Удалить выбранные', 'remove', 'danger') ?>

        <div class="box-tools">
            <?= PaginationHelper::pageSizeSelector($dataProvider->pagination) ?>
        </div>
    </div>
    <div class="box-body table-responsive">
        <?= $this->render('_tabs', ['tab' => 'moderation']) ?>

        <?= GridView::widget([
            'id' => 'grid',
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => \yii\grid\CheckboxColumn::class],
                ['class' => \yii\grid\SerialColumn::class],
                [
                    'attribute' => 'name',
                    'value' => function (Trade $trade) {
                        return Html::a(Html::encode($trade->name), $trade->getUrl());
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'user',
                    'label' => 'Компания',
                    'value' => function (Trade $trade) {
                        return $trade->user_id . ' ' . Html::a($trade->user->getVisibleName(), ['/user/view', 'id' => $trade->user_id]);
                    },
                    'format' => 'raw',
                ],
                'created_at:datetime:Добавлен',
                [
                    'attribute' => 'category_id',
                    'value' => function (Trade $trade) {
                        return Html::encode($trade->category->name);
                    },
                    'format' => 'raw',
                ],
                ['class' => \core\grid\ModeratorActionColumn::class],
            ],
        ]); ?>
    </div>
    <div class="box-footer">
        <?= HtmlHelper::actionButtonForSelected('Разместить выбранные', 'publish', 'primary') ?>
        <?= HtmlHelper::actionButtonForSelected('Удалить выбранные', 'remove', 'danger') ?>
    </div>
</div>
