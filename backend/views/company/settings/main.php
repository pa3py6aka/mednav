<?php

use core\components\SettingsManager;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \yii\base\Model */

?>
<?= $form->field($model, SettingsManager::COMPANY_NAME)->textInput() ?>
<?= $form->field($model, SettingsManager::COMPANY_NAME_UP)->textInput() ?>
<?= $form->field($model, SettingsManager::COMPANY_PAGE_SIZE)->input('number') ?>
<?= $form->field($model, SettingsManager::COMPANY_SMALL_SIZE)->input('number') ?>
<?= $form->field($model, SettingsManager::COMPANY_BIG_SIZE)->input('number') ?>
<?= $form->field($model, SettingsManager::COMPANY_MAX_SIZE)->input('number') ?>
<?= $form->field($model, SettingsManager::COMPANY_MODERATION)->checkbox() ?>
