<?php

use core\components\SettingsManager;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model \core\forms\manage\User\UserSettingsForm */

$this->title = 'Настройки пользователей';

?>
<?php $form = ActiveForm::begin() ?>
<div class="box box-primary">
    <div class="box-body table-responsive">
        <?= $form->field($model, SettingsManager::USER_EMAIL_ACTIVATION)->checkbox() ?>
        <?= $form->field($model, SettingsManager::USER_PREMODERATION)->checkbox() ?>
    </div>
    <div class="box-footer">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
