<?php

use core\components\SettingsManager;
use mihaildev\ckeditor\CKEditor;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \core\forms\manage\Board\BoardSettingsIndexForm */

?>
<?= $form->field($model, SettingsManager::BOARD_META_TITLE)->textInput() ?>
<?= $form->field($model, SettingsManager::BOARD_META_DESCRIPTION)->textarea(['rows' => 4]) ?>
<?= $form->field($model, SettingsManager::BOARD_META_KEYWORDS)->textarea(['rows' => 4]) ?>
<?= $form->field($model, SettingsManager::BOARD_TITLE)->textInput() ?>
<?= $form->field($model, SettingsManager::BOARD_DESCRIPTION_TOP)
    ->widget(CKEditor::class,['editorOptions' => ['preset' => 'full']])
    ->label('Описание сверху &nbsp; &nbsp;' . Html::activeCheckbox($model, SettingsManager::BOARD_DESCRIPTION_TOP_ON)) ?>
<?= $form->field($model, SettingsManager::BOARD_DESCRIPTION_BOTTOM)
    ->widget(CKEditor::class,['editorOptions' => ['preset' => 'full']])
    ->label('Описание снизу &nbsp; &nbsp;' . Html::activeCheckbox($model, SettingsManager::BOARD_DESCRIPTION_BOTTOM_ON)) ?>
