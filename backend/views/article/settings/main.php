<?php

use core\components\SettingsManager;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \yii\base\Model */

?>
<?= $form->field($model, SettingsManager::ARTICLE_NAME)->textInput() ?>
<?= $form->field($model, SettingsManager::ARTICLE_NAME_UP)->textInput() ?>
<?= $form->field($model, SettingsManager::ARTICLE_PAGE_SIZE)->input('number') ?>
<?= $form->field($model, SettingsManager::ARTICLE_SMALL_SIZE)->input('number') ?>
<?= $form->field($model, SettingsManager::ARTICLE_BIG_SIZE)->input('number') ?>
<?= $form->field($model, SettingsManager::ARTICLE_MAX_SIZE)->input('number') ?>
<?= $form->field($model, SettingsManager::ARTICLE_MODERATION)->checkbox() ?>
