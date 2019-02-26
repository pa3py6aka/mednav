<?php

use core\components\Settings;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \yii\base\Model */

?>
<?= $form->field($model, Settings::TRADE_NAME)->textInput() ?>
<?= $form->field($model, Settings::TRADE_NAME_UP)->textInput() ?>
<?= $form->field($model, Settings::TRADE_PAGE_SIZE)->input('number') ?>
<?= $form->field($model, Settings::TRADE_SMALL_SIZE)->input('number') ?>
<?= $form->field($model, Settings::TRADE_BIG_SIZE)->input('number') ?>
<?= $form->field($model, Settings::TRADE_MAX_SIZE)->input('number') ?>
<?= $form->field($model, Settings::TRADE_MODERATION)->checkbox() ?>
<?= $form->field($model, Settings::TRADE_SHOW_EMPTY_CATEGORIES)->checkbox() ?>
