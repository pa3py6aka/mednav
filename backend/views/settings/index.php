<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use core\components\Settings;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $tab string */
/* @var $model \core\forms\manage\GeneralSettingsForm */

$this->title = 'Основные настройки сайта';

?>
<?php $form = ActiveForm::begin() ?>
<div class="box box-primary">
    <div class="box-body table-responsive">
        <?= $form->field($model, Settings::GENERAL_TITLE)->textInput() ?>
        <?= $form->field($model, Settings::GENERAL_DESCRIPTION)->textInput() ?>
        <?= $form->field($model, Settings::GENERAL_KEYWORDS)->textInput() ?>
        <?= $form->field($model, Settings::GENERAL_EMAIL)->input('email') ?>
        <?= $form->field($model, Settings::GENERAL_EMAIL_FROM)->textInput() ?>
        <?= $form->field($model, Settings::GENERAL_MODALS_SHOWTIME)->input('number') ?>
        <?= $form->field($model, Settings::GENERAL_CONTACT_EMAIL)->input('email') ?>
    </div>
    <div class="box-footer">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
