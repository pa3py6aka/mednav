<?php

use core\components\SettingsManager;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \yii\base\Model */

?>
<?= $form->field($model, SettingsManager::BRANDS_NAME)->textInput() ?>
<?= $form->field($model, SettingsManager::BRANDS_NAME_UP)->textInput() ?>
<?= $form->field($model, SettingsManager::BRANDS_PAGE_SIZE)->input('number') ?>
<?= $form->field($model, SettingsManager::BRANDS_SMALL_SIZE)->input('number') ?>
<?= $form->field($model, SettingsManager::BRANDS_BIG_SIZE)->input('number') ?>
<?= $form->field($model, SettingsManager::BRANDS_MAX_SIZE)->input('number') ?>
<?= $form->field($model, SettingsManager::BRANDS_MODERATION)->checkbox() ?>
