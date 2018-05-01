<?php

use core\entities\Board\BoardCategory;
use core\grid\TreeViewColumn;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Разделы';
$this->params['breadcrumbs'][] = $this->title;

\backend\assets\TreeViewAsset::register($this);


?>
<div class="box">
    <div class="box-header">
        <?= Html::a('Добавить раздел', ['create'], ['class' => 'btn btn-success']) ?>
    </div>
    <div class="box-body table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'id',
                [
                    'attribute' => 'name',
                    'class' => TreeViewColumn::class,
                    'entity' => BoardCategory::class,
                ],
                ['class' => ActionColumn::class, 'buttons' => [
                    'geo' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-globe"></span>', ['/board/category/update', 'id' => $model->id, 'tab' => 'geo']);
                    }
                ], 'template' => '{geo} {delete}'],
            ],
        ]); ?>
    </div>
</div>