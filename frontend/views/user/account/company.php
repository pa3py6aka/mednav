<?php

use core\components\ImageManager\ImageManagerAsset;
use kartik\file\FileInput;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use frontend\widgets\RegionsModalWidget;
use frontend\widgets\CompanyCategoriesSelectWidget;
use mihaildev\ckeditor\CKEditor;
use core\helpers\HtmlHelper;
use frontend\widgets\PhotosManagerWidget;
use core\actions\UploadAction;

/* @var $this yii\web\View */
/* @var $user \core\entities\User\User int */
/* @var $model \core\forms\Company\CompanyForm */
/* @var $tab string */

$this->registerJsVar('_ImageUploadAction', Url::to(['/user/account/company-photo-upload']));
ImageManagerAsset::register($this);

$this->title = 'Личный кабинет | Моя компания';

?>
<div class="row">
    <div class="col-md-3">
        <?= $this->render('@frontend/views/user/_left_menu', ['user' => $user]) ?>
    </div>

    <div class="col-md-9">
        <?= \frontend\widgets\AccountBreadcrumbs::show(['Моя компания']) ?>

        <?php if ($model->company && $model->company->isOnModeration()): ?>
            <div class="alert alert-info" role="alert" style="margin-top: 10px;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Закрыть"><span aria-hidden="true">&times;</span></button>
                Ваша компания находится на проверке.
            </div>
        <?php endif; ?>

        <h1>Моя компания</h1>

        <?php if ($model->company): ?>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"<?= HtmlHelper::tabStatus('main', $tab) ?>><a href="#main" aria-controls="main" role="tab" data-toggle="tab">Данные компании</a></li>
            <li role="presentation"<?= HtmlHelper::tabStatus('photos', $tab) ?>><a href="#photos" aria-controls="photos" role="tab" data-toggle="tab">Фотографии</a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane<?= HtmlHelper::active($tab, 'main') ?>" id="main">
                <br>
        <?php endif; ?>

        <?php $form = ActiveForm::begin(['id' => 'company-form']) ?>

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
                'initialPreview' => $model->logo ? [$model->logo] : false,
                'initialPreviewAsData'=>true,
                'showPreview' => (bool) $model->logo,
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

        <?= $form->field($model, 'description')->widget(CKEditor::class, \core\helpers\EditorHelper::minimumPreset()) ?>

        <?= $form->field($model, 'tags')->textInput(['maxlength' => true]) ?>

        <?php if (!$model->company): ?>
            <?= UploadAction::htmlBlock($model->formName()) ?>
        <?php endif; ?>

        <button type="submit" class="btn btn-primary">Сохранить</button>

        <?= CompanyCategoriesSelectWidget::widget(['model' => $model]) ?>

        <?php ActiveForm::end() ?>

        <?php if ($model->company): ?>
            </div>
            <!-- Фотографии -->
            <div role="tabpanel" class="tab-pane<?= HtmlHelper::active($tab, 'photos') ?>" id="photos">
                <br>
                <?= PhotosManagerWidget::widget(['entityId' => $model->company->id, 'photos' => $model->company->photos]) ?>
            </div>
        </div>

        <?php endif; ?>
    </div>
</div>

<?= RegionsModalWidget::widget() ?>