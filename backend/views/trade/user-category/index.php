<?php

use yii\helpers\Html;
use yii\grid\GridView;
use core\entities\Trade\TradeUserCategory;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\TradeUserCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользовательские категории';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trade-user-category-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Добавить категорию', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                //'id',
                [
                    'attribute' => 'name',
                    'value' => function (TradeUserCategory $userCategory) {
                        return Html::a(Html::encode($userCategory->name), ['view', 'id' => $userCategory->id]) . ' (' . count($userCategory->trades) . ')';
                    },
                    'format' => 'raw',
                ],
                [
                    'class' => \core\grid\CategoryColumn::class,
                    'url' => ['/trade/category/update', 'id' => '{id}'],
                    'filter' => \core\forms\manage\Trade\TradeCategoryForm::parentCategoriesList(\core\entities\Trade\TradeCategory::class, false, false)
                ],
                [
                    'class' => \core\grid\UserNameColumn::class,
                    'label' => 'Компания',
                    'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'placeholder' => 'ID/email/название компании пользователя'],
                ],
                //'uom_id',
                // 'currency_id',
                // 'wholesale',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
