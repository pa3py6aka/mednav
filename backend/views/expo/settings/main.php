<?php

use core\components\SettingsManager;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \yii\base\Model */

?>
<?= $form->field($model, SettingsManager::EXPO_NAME)->textInput() ?>
<?= $form->field($model, SettingsManager::EXPO_NAME_UP)->textInput() ?>
<?= $form->field($model, SettingsManager::EXPO_PAGE_SIZE)->input('number') ?>
<?= $form->field($model, SettingsManager::EXPO_SMALL_SIZE)->input('number') ?>
<?= $form->field($model, SettingsManager::EXPO_BIG_SIZE)->input('number') ?>
<?= $form->field($model, SettingsManager::EXPO_MAX_SIZE)->input('number') ?>
<?= $form->field($model, SettingsManager::EXPO_MODERATION)->checkbox() ?>
