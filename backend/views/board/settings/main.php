<?php

use core\components\SettingsManager;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \yii\base\Model */

?>
<?= $form->field($model, SettingsManager::BOARD_NAME)->textInput() ?>
<?= $form->field($model, SettingsManager::BOARD_NAME_UP)->textInput() ?>
<?= $form->field($model, SettingsManager::BOARD_PAGE_SIZE)->input('number') ?>
<?= $form->field($model, SettingsManager::BOARD_SMALL_SIZE)->input('number') ?>
<?= $form->field($model, SettingsManager::BOARD_BIG_SIZE)->input('number') ?>
<?= $form->field($model, SettingsManager::BOARD_MAX_SIZE)->input('number') ?>
<?= $form->field($model, SettingsManager::BOARD_MODERATION)->checkbox() ?>
