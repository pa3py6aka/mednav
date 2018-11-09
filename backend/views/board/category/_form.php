<?php

use core\entities\Board\BoardParameter;
use core\helpers\MarkHelper;
use core\helpers\PaginationHelper;
use mihaildev\ckeditor\CKEditor;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use core\entities\Board\BoardCategory;

/* @var $this yii\web\View */
/* @var $model \core\forms\manage\Board\BoardCategoryForm */
/* @var $form yii\widgets\ActiveForm */

MarkHelper::js($this);
?>

<div class="k-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-body">
            <?= $form->field($model, 'parentId')->dropDownList($model->parentCategoriesList(BoardCategory::class)) ?>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'contextName')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'enabled')->checkbox() ?>
            <?= $form->field($model, 'notShowOnMain')->checkbox() ?>
            <?= $form->field($model, 'childrenOnlyParent')->checkbox() ?>
            <?= $form->field($model, 'parameters')
                ->checkboxList(ArrayHelper::map(BoardParameter::find()->asArray()->all(), 'id', 'name'), [
                    'item' => function($index, $label, $name, $checked, $value) {
                        $disable = false;
                        $hiddenType = '';
                        if ($value == '1') {
                            //$checked = true;
                            //$disable = true;
                            //$hiddenType = Html::hiddenInput($name, $value);
                        }
                        $checkbox = Html::checkbox($name, $checked, ['value' => $value, 'disabled' => $disable]);
                        return $hiddenType . Html::label($checkbox . ' ' . $label);
                    }
                ]) ?>
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
                ->textInput(['maxlength' => true, 'id' => 'metaTitleItem'])
                ->hint(MarkHelper::links(MarkHelper::MARKS_BOARD, 'metaTitleItem')) ?>
            <?= $form->field($model, 'metaDescriptionItem')
                ->textarea(['rows' => 4, 'id' => 'metaDescriptionItem'])
                ->hint(MarkHelper::links(MarkHelper::MARKS_BOARD, 'metaDescriptionItem')) ?>

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

