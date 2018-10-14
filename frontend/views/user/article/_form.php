<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use core\entities\Article\ArticleCategory;
use core\actions\UploadAction;

/* @var $this yii\web\View */
/* @var $model \core\forms\Article\ArticleForm */

?>
<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'categoryId')->dropDownList(\core\forms\manage\CategoryForm::parentCategoriesList(ArticleCategory::class)) ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'intro')->textarea(['rows' => 4]) ?>

<?= $form->field($model, 'fullText')->widget(CKEditor::class, Yii::$app->params['CKEditorPreset']) ?>

<?= $form->field($model, 'tags')->textInput() ?>

<?php if (!$model->article): ?>
    <?= UploadAction::htmlBlock($model->formName()) ?>
<?php endif; ?>

<?= Html::submitButton($model->article ? 'Сохранить' : 'Добавить', ['class' => 'btn btn-success btn-flat']) ?>

<?php ActiveForm::end(); ?>
