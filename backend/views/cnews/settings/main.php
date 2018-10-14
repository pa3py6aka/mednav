<?php

use core\components\SettingsManager;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \yii\base\Model */

?>
<?= $form->field($model, SettingsManager::CNEWS_NAME)->textInput() ?>
<?= $form->field($model, SettingsManager::CNEWS_NAME_UP)->textInput() ?>
<?= $form->field($model, SettingsManager::CNEWS_PAGE_SIZE)->input('number') ?>
<?= $form->field($model, SettingsManager::CNEWS_SMALL_SIZE)->input('number') ?>
<?= $form->field($model, SettingsManager::CNEWS_BIG_SIZE)->input('number') ?>
<?= $form->field($model, SettingsManager::CNEWS_MAX_SIZE)->input('number') ?>
<?= $form->field($model, SettingsManager::CNEWS_MODERATION)->checkbox() ?>
