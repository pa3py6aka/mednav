<?php

use core\components\SettingsManager;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \yii\base\Model */

?>
<?= $form->field($model, SettingsManager::TRADE_NAME)->textInput() ?>
<?= $form->field($model, SettingsManager::TRADE_NAME_UP)->textInput() ?>
<?= $form->field($model, SettingsManager::TRADE_PAGE_SIZE)->input('number') ?>
<?= $form->field($model, SettingsManager::TRADE_SMALL_SIZE)->input('number') ?>
<?= $form->field($model, SettingsManager::TRADE_BIG_SIZE)->input('number') ?>
<?= $form->field($model, SettingsManager::TRADE_MAX_SIZE)->input('number') ?>
<?= $form->field($model, SettingsManager::TRADE_MODERATION)->checkbox() ?>
