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
            <?= $form->field($model, 'form')->textInput(['maxlength' => true, 'placeholder' => 'Форма(ООО,ИП..)'])->label(false) ?>
        </div>
        <div class="col-sm-9 no-right-padding">
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

        <?= $form->field($model, 'description')->widget(CKEditor::class, Yii::$app->params['CKEditorPreset']) ?>

        <?= $form->field($model, 'tags')->textInput(['maxlength' => true]) ?>

        <?php if (!$user->company): ?>
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