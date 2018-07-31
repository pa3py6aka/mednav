<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $blocks \core\entities\Context[] */

$this->title = 'Контекстные блоки';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="box">
    <!--<div class="box-header">
        <?= Html::a('Добавить блок', ['create'], ['class' => 'btn btn-success']) ?>
    </div>-->
    <div class="box-body table-responsive">
        <?= Html::beginForm() ?>
        <?php foreach ($blocks as $block): ?>
            <div>
                <?= Html::hiddenInput("enable[{$block->id}]", 0) ?>
                <?= Html::checkbox("enable[{$block->id}]", $block->enable, ['label' => 'Блок ' . $block->id]) ?>
                <?= Html::textarea("html[{$block->id}]", $block->html, ['class' => 'form-control', 'rows' => 5]) ?>
            </div>
        <?php endforeach; ?>
        <br>
        <div class="form-group">
            <button type="submit" class="btn btn-success">Сохранить</button>
        </div>
        <?= Html::endForm() ?>
    </div>
</div>