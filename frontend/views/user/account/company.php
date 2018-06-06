<?php

use kartik\file\FileInput;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $user \core\entities\User\User int */
/* @var $model \core\forms\Company\CompanyForm */

$this->title = 'Личный кабинет | Моя компания';

?>
<div class="row">
    <div class="col-md-3">
        <?= $this->render('@frontend/views/user/_left_menu', ['user' => $user]) ?>
    </div>

    <div class="col-md-9">
        <?= \frontend\widgets\AccountBreadcrumbs::show(['Моя компания']) ?>
        <h1>Моя компания</h1>

        <?php $form = ActiveForm::begin() ?>

        <div class="col-sm-3 no-padding">
            <?= $form->field($model, 'form')->textInput(['maxlength' => true, 'placeholder' => 'Форма(ООО,ИП..)'])->label(false) ?>
        </div>
        <div class="col-sm-9 no-right-padding">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => 'Название компании'])->label(false) ?>
        </div>

        <?= $form->field($model, 'categoriesHint')->hiddenInput()
            ->hint('<span class="input-modal-link" data-toggle="modal" data-target="#caterigoriesSelectModal">' . $model->categorySelectionString() . '</span>'); ?>

        <?= $form->field($model, 'logo')->widget(FileInput::class, [
            'pluginOptions' => [
                'showPreview' => false,
                'showCaption' => true,
                'showRemove' => true,
                'showUpload' => false
            ]
        ]) ?>

        <?= $form->field($model, 'site')->textInput(['maxlength' => true]) ?>

        <button type="submit" class="btn btn-primary">Сохранить</button>
        <?php ActiveForm::end() ?>
    </div>
</div>

<?= \frontend\widgets\CompanyCategoriesSelectWidget::widget(['model' => $model]) ?>