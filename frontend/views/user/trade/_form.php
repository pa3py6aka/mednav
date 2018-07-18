<?php

use core\forms\manage\Trade\TradeManageForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use core\entities\Trade\TradeUserCategory;
use frontend\widgets\WholesalesFormWidget;

/* @var $this yii\web\View */
/* @var $model TradeManageForm */


?>
<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'categoryId')
    ->dropDownList(ArrayHelper::map(TradeUserCategory::find()->asArray()->all(), 'id', 'name'), [
        'prompt' => '',
        'id' => 'category-selector'
    ]) ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'price', ['template' => "{label}<br>\n{hint}\n{input}<div style='display:inline-block;padding-left:10px;' id='price-currency'></div>\n{error}"])
    ->textInput(['maxlength' => true, 'style' => 'width:85%;display:inline-block;']) ?>

<?= WholesalesFormWidget::widget(['model' => $model]) ?>

<?= $form->field($model, 'stock')->checkbox() ?>

<?= $form->field($model, 'note')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'description')->widget(CKEditor::class, Yii::$app->params['CKEditorPreset']) ?>

<?= $form->field($model, 'tags')->textInput() ?>

<?php if ($model->scenario == TradeManageForm::SCENARIO_USER_CREATE): ?>
    <label class="control-label" for="file">Фото</label>
    <div class="photos-block" data-form-name="<?= $model->formName() ?>" data-attribute="photos">
        <div class="add-image-item has-overlay">
            <img src="/img/add_image.png" alt="Добафить фото" class="add-image-img">
            <input type="file" class="hidden" accept="image/*">
            <span class="remove-btn fa fa-remove hidden"></span>
        </div>
        <div class="help-block"></div>
    </div>
<?php endif; ?>

<?= Html::submitButton(1 ? 'Сохранить' : 'Добавить', ['class' => 'btn btn-success btn-flat']) ?>

<?php ActiveForm::end(); ?>
