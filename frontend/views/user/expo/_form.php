<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use core\entities\Expo\ExpoCategory;
use core\actions\UploadAction;

/* @var $this yii\web\View */
/* @var $model \core\forms\Expo\ExpoForm */

?>
<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'categoryId')->dropDownList(\core\forms\manage\CategoryForm::parentCategoriesList(ExpoCategory::class)) ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'intro')->textarea(['rows' => 4]) ?>

<?= $form->field($model, 'fullText')->widget(CKEditor::class, Yii::$app->params['CKEditorPreset']) ?>

<?= $form->field($model, 'showDates')->checkbox() ?>

<?= $form->field($model, 'startDate')->widget(\kartik\datetime\DateTimePicker::class, [
    'convertFormat' => true,
    'pluginOptions' => [
        'format' => 'php:d.m.Y H:i',
        'autoclose' => true,
        'todayHighlight' => true
    ]
]) ?>

<?= $form->field($model, 'endDate')->widget(\kartik\datetime\DateTimePicker::class, [
    'convertFormat' => true,
    'pluginOptions' => [
        'format' => 'php:d.m.Y H:i',
        'autoclose' => true,
        'todayHighlight' => true
    ]
]) ?>

<?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'tags')->textInput() ?>

<?php if (!$model->article): ?>
    <?= UploadAction::htmlBlock($model->formName()) ?>
<?php endif; ?>

<?= Html::submitButton($model->article ? 'Сохранить' : 'Добавить', ['class' => 'btn btn-success btn-flat']) ?>

<?php ActiveForm::end(); ?>
