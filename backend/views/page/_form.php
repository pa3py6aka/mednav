<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model \core\forms\PageForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="page-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'metaTitle')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'metaDescription')->textarea(['rows' => 4]) ?>

        <?= $form->field($model, 'metaKeywords')->textarea(['rows' => 4]) ?>

        <?= $form->field($model, 'content')->widget(CKEditor::class) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
