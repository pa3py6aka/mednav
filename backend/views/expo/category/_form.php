<?php

use core\helpers\PaginationHelper;
use core\entities\Expo\ExpoCategory;
use mihaildev\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \core\forms\manage\Expo\ExpoCategoryForm */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="k-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-body">
            <?= $form->field($model, 'parentId')->dropDownList($model->parentCategoriesList(ExpoCategory::class)) ?>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'contextName')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'enabled')->checkbox(['label' => 'Доступен для добавления выставок']) ?>
            <?= $form->field($model, 'notShowOnMain')->checkbox() ?>
            <?= $form->field($model, 'childrenOnlyParent')->checkbox() ?>
            <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'metaTitle')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'metaDescription')->textarea(['rows' => 4]) ?>
            <?= $form->field($model, 'metaKeywords')->textarea(['rows' => 4]) ?>
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'descriptionTop')
                ->widget(CKEditor::class,['editorOptions' => ['preset' => 'full']])
                ->label('Описание вверху &nbsp; &nbsp;' . Html::activeCheckbox($model, 'descriptionTopOn')) ?>
            <?= $form->field($model, 'descriptionBottom')
                ->widget(CKEditor::class,['editorOptions' => ['preset' => 'full']])
                ->label('Описание снизу &nbsp; &nbsp;' . Html::activeCheckbox($model, 'descriptionBottomOn')) ?>
            <?= $form->field($model, 'metaTitleItem')
                ->textInput(['maxlength' => true, 'id' => 'metaTitleItem']) ?>
            <?= $form->field($model, 'metaDescriptionItem')
                ->textarea(['rows' => 4, 'id' => 'metaDescriptionItem']) ?>

            <?= $form->field($model, 'metaTitleOther')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'metaDescriptionOther')->textarea(['rows' => 4]) ?>
            <?= $form->field($model, 'metaKeywordsOther')->textInput() ?>
            <?= $form->field($model, 'titleOther')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'pagination')->dropDownList(PaginationHelper::paginationTypes()) ?>
            <?= $form->field($model, 'active')->checkbox() ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

