<?php

use core\forms\manage\Board\BoardManageForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use core\entities\Currency;
use core\entities\Board\BoardTerm;
use mihaildev\ckeditor\CKEditor;
use frontend\widgets\RegionsModalWidget;

/* @var $this yii\web\View */
/* @var $model BoardManageForm */

$this->registerJs($model->getJs());

?>
<?php $form = ActiveForm::begin(); ?>

<div id="category-block" class="box has-overlay">
    <?= $model->getCategoryDropdowns($form) ?>
</div>

<div id="parameters-block">
    <?= $model->getParametersBlock() ?>
</div>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'note')
    ->textInput(['maxlength' => true, 'placeholder' => 'Прим: Состояние Новый/БУ, страна производитель и др. информация']) ?>

<div class="container-fluid no-padding" style="box-shadow:none;">
    <div class="col-xs-6 no-padding">
        <?= $form->field($model, 'price')->textInput() ?>
    </div>
    <div class="col-xs-3" style="margin-top:24px;">
        <?= $form->field($model, 'currency')
            ->dropDownList(ArrayHelper::map(Currency::find()->asArray()->all(), 'id', 'sign'))->label(false) ?>
    </div>
    <div class="col-xs-3" style="margin-top:30px;">
        <?= Html::activeCheckbox($model, 'priceFrom') ?>
    </div>
</div>

<?= $form->field($model, 'fullText')->widget(CKEditor::class, Yii::$app->params['CKEditorPreset']) ?>

<?= $form->field($model, 'tags')->textInput() ?>

<?= $model->scenario == BoardManageForm::SCENARIO_USER_EDIT ? ''
    : $form->field($model, 'termId')
        ->dropDownList(ArrayHelper::map(BoardTerm::find()->asArray()->all(), 'id', 'daysHuman')) ?>

<?= $form->field($model, 'geoId')
    ->hiddenInput(['id' => 'form-geo-id'])
    ->hint('<span class="input-modal-link" id="region-select-link" data-toggle="modal" data-target="#modalRegion">' . $model->geoName() . '</span>'); ?>

<?php if ($model->scenario == BoardManageForm::SCENARIO_USER_CREATE): ?>
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

<div class="hidden" id="templates">
    <div class="form-group category-dropdown">
        <select class="form-control" name="" aria-invalid="true" title="Раздел">
            <option></option>
        </select>
    </div>
</div>

<?= RegionsModalWidget::widget() ?>
