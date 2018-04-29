<?php

use core\components\SettingsManager;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model \core\forms\manage\Geo\GeoSettingsForm */

$this->title = 'Настройки Geo';

?>
<?php $form = ActiveForm::begin() ?>
<div class="box box-primary">
    <div class="box-body table-responsive">
        <?= $form->field($model, SettingsManager::GEO_SORT_BY_COUNT)->checkbox() ?>
        <?= $form->field($model, SettingsManager::GEO_SORT_BY_ALP)->checkbox() ?>
    </div>
    <div class="box-footer">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
