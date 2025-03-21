<?php

use yii\helpers\Html;
use core\components\TreeManager\TreeManagerWidget;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Разделы статей';
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['/article/article/active']];
$this->params['breadcrumbs'][] = 'Разделы';

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