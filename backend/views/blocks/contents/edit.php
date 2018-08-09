<?php

use backend\assets\MultiSelectAsset;
use core\entities\ContentBlock;
use yii\helpers\ArrayHelper;

/* @var $this \yii\web\View */
/* @var $model \core\components\ContentBlocks\ContentBlockForm */


MultiSelectAsset::register($this);
$moduleName = ArrayHelper::getValue(ContentBlock::modulesArray(), $model->module, '?');
$this->title = "Редактирование блока модуля \"{$moduleName}\"";

//$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = ['label' => 'Контентные блоки', 'url' => ['index']];

?>
<div class="box box-primary">
    <?= $this->render('_form', ['model' => $model]) ?>
</div>


