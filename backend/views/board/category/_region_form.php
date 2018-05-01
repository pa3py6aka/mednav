<?php

use mihaildev\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \core\forms\manage\Board\BoardCategoryRegionForm */
/* @var $categoryId integer */
/* @var $regionId integer */

/* @var $form yii\widgets\ActiveForm */

?>

<?php $form = ActiveForm::begin(); ?>
    <?= Html::hiddenInput('entityId', $categoryId) ?>
    <?= Html::hiddenInput('regionId', $regionId) ?>
    <div class="box-body">
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
    </div>
    <div class="box-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
<?php ActiveForm::end(); ?>

