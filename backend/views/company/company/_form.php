<?php

use core\components\ImageManager\ImageManagerAsset;
use core\actions\UploadAction;
use frontend\widgets\RegionsModalWidget;
use mihaildev\ckeditor\CKEditor;
use kartik\file\FileInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\widgets\UserFieldWidget\UserIdFieldWidget;
use frontend\widgets\CompanyCategoriesSelectWidget;

/* @var $this yii\web\View */
/* @var $model \core\forms\Company\CompanyForm */

/* @var $form yii\widgets\ActiveForm */

$this->registerJsVar('_ImageUploadAction', Url::to(['/company/company/upload']));
ImageManagerAsset::register($this);

?>
<div class="company-form box box-primary">
    <?php $form = ActiveForm::begin(['id' => 'company-form']); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'user_id')->widget(UserIdFieldWidget::class) ?>

        <div class="col-sm-3 no-padding">
            <?= $form->field($model, 'form')->textInput(['maxlength' => true, 'placeholder' => 'Форма(ООО,ИП..)'])->label("Название") ?>
        </div>
        <div class="col-sm-9 no-right-padding" style="padding-top: 25px;">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Название компании'])->label(false) ?>
        </div>

        <?= $form->field($model, 'categoriesHint')->hiddenInput(['id' => 'compCategory0'])
            ->hint('<span class="input-modal-link" data-toggle="modal" data-target="#caterigoriesSelectModal">' . $model->categorySelectionString() . '</span>'); ?>

        <?= $form->field($model, 'logo')->widget(FileInput::class, [
            'pluginOptions' => [
                'showPreview' => false,
                'showCaption' => true,
                'showRemove' => true,
                'showUpload' => false
            ],
            'options' => ['accept' => 'image/*'],
        ]) ?>

        <?= $form->field($model, 'site')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'geoId')
            ->hiddenInput(['id' => 'form-geo-id'])
            ->hint('<span class="input-modal-link" id="region-select-link" data-toggle="modal" data-target="#modalRegion">' . $model->geoName() . '</span>'); ?>

        <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

        <div class="col-sm-4 no-padding">
            <?= $form->field($model, 'phones[0]')->textInput(['maxlength' => 25]) ?>
        </div>
        <div class="col-sm-4 no-right-padding" style="margin-top:25px;">
            <?= $form->field($model, 'phones[1]')->textInput(['maxlength' => 25])->label(false) ?>
        </div>
        <div class="col-sm-4 no-right-padding" style="margin-top:25px;">
            <?= $form->field($model, 'phones[2]')->textInput(['maxlength' => 25])->label(false) ?>
        </div>

        <?= $form->field($model, 'fax')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'info')->textarea(['rows' => 3]) ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'shortDescription')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'description')->widget(CKEditor::class) ?>

        <?= $form->field($model, 'tags')->textInput(['maxlength' => true]) ?>

        <?php if (!$model->company): ?>
            <?= UploadAction::htmlBlock($model->formName()) ?>
        <?php endif; ?>

        <?= CompanyCategoriesSelectWidget::widget(['model' => $model]) ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton($model->company ? 'Сохранить' : 'Добавить', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?= RegionsModalWidget::widget() ?>