<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\grid\CheckboxColumn;
use core\entities\Trade\Trade;
use core\helpers\CategoryHelper;
use core\entities\Trade\TradeUserCategory;

/* @var $this yii\web\View */
/* @var $model TradeUserCategory */
/* @var $tradesProvider \yii\data\ActiveDataProvider */
/* @var $tradesSearch \backend\forms\TradeSearch */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Пользовательские категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trade-user-category-view box box-primary">
    <div class="box-header">
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => 'Удалить данную категорию со всеми товарами?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
    <div class="box-body table-responsive">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                [
                    'attribute' => 'user_id',
                    'label' => 'Компания',
                    'value' => function (TradeUserCategory $userCategory) {
                        $user = $userCategory->getOwnerUser();
                        if ($user->isCompany() && $user->isCompanyActive()) {
                            return $user->company->id . ' ' . Html::a($user->company->getFullName(), ['/company/company/view', 'id' => $user->company->id], ['target' => '_blank']);
                        }

                        return $user->id . ' ' . Html::a($user->getUserName(), ['/user/view', 'id' => $user->id], ['target' => '_blank']);
                    },
                    'format' => 'raw',
                ],
                'name',
                [
                    'attribute' => 'category_id',
                    'value' => function (TradeUserCategory $userCategory) {
                        $cats = [];
                        foreach ($userCategory->category->getParents()->all() as $parent) {
                            if ($parent->depth > 0) {
                                $cats[] = Html::a($parent->name, ['/trade/category/update', 'id' => $parent->id], ['target' => '_blank']);
                            }
                        }
                        $cats[] = Html::a($userCategory->category->name, ['/trade/category/update', 'id' => $userCategory->category->id], ['target' => '_blank']);
                        return implode(' / ', $cats);
                    },
                    'format' => 'raw',
                ],
                'uomName',
                'currencyName',
                'wholesale:boolean',
            ],
        ]) ?>
    </div>
</div>
<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Товары</h3>
    </div>
    <div class="box-body table-responsive">
        <?= GridView::widget([
            'id' => 'grid',
            'dataProvider' => $tradesProvider,
            'filterModel' => $tradesSearch,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                //['class' => CheckboxColumn::class],
                ['class' => \yii\grid\SerialColumn::class],
                [
                    'attribute' => 'name',
                    'value' => function (Trade $trade) {
                        return '<div style="width:400px;">' . Html::a($trade->name, ['/trade/trade/view', 'id' => $trade->id]) . '</div>';
                    },
                    'contentOptions' => ['style' => 'white-space:normal;'],
                    'format' => 'raw',
                ],
                /*[
                    'attribute' => 'company_id',
                    'label' => 'Компания',
                    'value' => function (Trade $trade) {
                        return $trade->company_id . ' ' . Html::a($trade->company->getFullName(), ['/company/company/view', 'id' => $trade->company_id]);
                    },
                    'format' => 'raw',
                ],*/
                'created_at:datetime:Добавлен',
                /*[
                    'attribute' => 'category_id',
                    'value' => function (Trade $trade) {
                        return Html::a($trade->category->name, ['/trade/category/update', 'id' => $trade->category_id], [
                            'data-toggle' => 'tooltip',
                            'title' => CategoryHelper::categoryParentsString($trade->category),
                        ]);
                    },
                    'format' => 'raw',
                    'filter' => \core\forms\manage\CategoryForm::parentCategoriesList(\core\entities\Trade\TradeCategory::class, false, false)
                ],*/
                [
                    'class' => \yii\grid\ActionColumn::class,
                    'template' => '{update} {delete}',
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            return Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['/trade/trade/update', 'id' => $model->id]);
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::a('<i class="glyphicon glyphicon-trash"></i>', ['/trade/trade/delete', 'id' => $model->id], ['data-method' => 'post', 'data-confirm' => 'Удалить данный товар?']);
                        },
                    ],
                ],
            ],
        ]); ?>
    </div>
</div>
