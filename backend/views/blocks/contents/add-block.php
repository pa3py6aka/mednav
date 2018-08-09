<?php

use backend\assets\MultiSelectAsset;
use core\entities\ContentBlock;
use yii\helpers\ArrayHelper;

/* @var $this \yii\web\View */
/* @var $model \core\components\ContentBlocks\ContentBlockForm */

MultiSelectAsset::register($this);
$moduleName = ArrayHelper::getValue(ContentBlock::modulesArray(), $model->module, '?');
$this->title = "Добавление блока для модуля \"{$moduleName}\"";

?>
<div class="box box-primary">
    <?= $this->render('_form', ['model' => $model]) ?>
</div>