<?php

use core\forms\manage\Trade\TradeManageForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use core\entities\Trade\TradeUserCategory;
use frontend\widgets\WholesalesFormWidget;
use core\actions\UploadAction;

/* @var $this yii\web\View */
/* @var $model TradeManageForm */

?>
<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'categoryId')
    ->dropDownList($model->getUserCategories(), [
        'prompt' => '',
        'id' => 'category-selector'
    ]) ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'price', ['template' => "{label}<br>\n{hint}\n{input}<div style='display:inline-block;padding-left:10px;' id='price-currency'></div>\n{error}"])
    ->textInput(['maxlength' => true, 'style' => 'width:85%;display:inline-block;']) ?>

<?= WholesalesFormWidget::widget(['model' => $model]) ?>

<?= $form->field($model, 'stock')->checkbox() ?>

<?= $form->field($model, 'externalLink')->textInput(['maxlength' => true, 'placeholder' => 'http://site.ru/tovar.html']) ?>

<?= $form->field($model, 'note')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'description')->widget(CKEditor::class, Yii::$app->params['CKEditorPreset']) ?>

<?= $form->field($model, 'tags')->textInput() ?>

<?php if ($model->scenario == TradeManageForm::SCENARIO_USER_CREATE): ?>
    <?= UploadAction::htmlBlock($model->formName()) ?>
<?php endif; ?>

<?= Html::submitButton(1 ? 'Сохранить' : 'Добавить', ['class' => 'btn btn-success btn-flat']) ?>

<?php ActiveForm::end(); ?>
