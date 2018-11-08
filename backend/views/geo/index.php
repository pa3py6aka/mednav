<?php

use core\entities\Geo;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\GeoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Гео';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="box">
    <div class="box-header">
        <?= Html::a('Добавить регион', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Настройки', ['settings'], ['class' => 'btn btn-primary']) ?>
    </div>
    <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                //'id',
                [
                    'attribute' => 'name',
                    'value' => function (Geo $model) {
                        $indent = ($model->depth > 1 ? str_repeat('-', $model->depth - 1) . ' ' : '');
                        return $indent . Html::a(Html::encode($model->name), ['update', 'id' => $model->id]);
                    },
                    'format' => 'raw',
                ],
                'slug',
                [
                    'attribute' => 'popular',
                    'format' => 'boolean',
                    'filter' => [0 => 'Нет', 1 => 'Да']
                ],
                [
                    'attribute' => 'active',
                    'format' => 'boolean',
                    'filter' => [0 => 'Нет', 1 => 'Да']
                ],
                ['class' => ActionColumn::class, 'template' => "{delete}"],
            ],
        ]); ?>
    </div>
</div>