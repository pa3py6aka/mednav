<?php

use core\components\Settings;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \yii\base\Model */

?>
<?= $form->field($model, Settings::BOARD_NAME)->textInput() ?>
<?= $form->field($model, Settings::BOARD_NAME_UP)->textInput() ?>
<?= $form->field($model, Settings::BOARD_PAGE_SIZE)->input('number') ?>
<?= $form->field($model, Settings::BOARD_SMALL_SIZE)->input('number') ?>
<?= $form->field($model, Settings::BOARD_BIG_SIZE)->input('number') ?>
<?= $form->field($model, Settings::BOARD_MAX_SIZE)->input('number') ?>
<?= $form->field($model, Settings::BOARD_MODERATION)->checkbox() ?>
<?= $form->field($model, Settings::BOARD_SHOW_EMPTY_CATEGORIES)->checkbox() ?>
<?= $form->field($model, Settings::BOARD_SHOW_ARCHIVE_UNITS)->checkbox() ?>
