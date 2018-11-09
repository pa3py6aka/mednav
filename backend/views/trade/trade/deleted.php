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
        <?= HtmlHelper::actionButtonForSelected('Опубликовать выбранные', 'publish', 'primary') ?>
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
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => CheckboxColumn::class],
                ['class' => \yii\grid\SerialColumn::class],
                [
                    'attribute' => 'name',
                    'value' => function (Trade $trade) {
                        return '<div style="width:400px;">' . Html::a($trade->name, ['/trade/trade/view', 'id' => $trade->id]) . '</div>';
                    },
                    'contentOptions' => ['style' => 'white-space:normal;'],
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'company_id',
                    'label' => 'Компания',
                    'value' => function (Trade $trade) {
                        return $trade->company_id . ' ' . Html::a($trade->company->getFullName(), ['/company/company/view', 'id' => $trade->company_id]);
                    },
                    'format' => 'raw',
                ],
                'created_at:datetime:Добавлен',
                [
                    'attribute' => 'category_id',
                    'value' => function (Trade $trade) {
                        return Html::a($trade->category->name, ['/trade/category/update', 'id' => $trade->category_id], [
                            'data-toggle' => 'tooltip',
                            'title' => CategoryHelper::categoryParentsString($trade->category),
                        ]);
                    },
                    'format' => 'raw',
                    'filter' => \core\forms\manage\CategoryForm::parentCategoriesList(\core\entities\Trade\TradeCategory::class, false, false)
                ],
                ['class' => \core\grid\ModeratorActionColumn::class],
            ],
        ]); ?>
    </div>
    <div class="box-footer">
        <?= HtmlHelper::actionButtonForSelected('Опубликовать выбранные', 'publish', 'primary') ?>
        <?= HtmlHelper::actionButtonForSelected('Удалить выбранные', 'remove', 'danger') ?>
    </div>
</div>
