<?php

use yii\helpers\Html;
use core\components\TreeManager\TreeManagerWidget;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Разделы';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="box">
    <div class="box-header">
        <?= Html::a('Добавить раздел', ['create'], ['class' => 'btn btn-success']) ?>
    </div>
    <div class="box-body table-responsive">
        <?= TreeManagerWidget::widget([
            'roots' => $dataProvider->models,
            'url' =>'/article/category',
        ]) ?>
    </div>
</div>