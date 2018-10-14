<?php

use core\components\SettingsManager;
use mihaildev\ckeditor\CKEditor;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \core\forms\manage\News\NewsSettingsIndexForm */

?>
<?= $form->field($model, SettingsManager::NEWS_META_TITLE)->textInput() ?>
<?= $form->field($model, SettingsManager::NEWS_META_DESCRIPTION)->textarea(['rows' => 4]) ?>
<?= $form->field($model, SettingsManager::NEWS_META_KEYWORDS)->textarea(['rows' => 4]) ?>
<?= $form->field($model, SettingsManager::NEWS_TITLE)->textInput() ?>
<?= $form->field($model, SettingsManager::NEWS_DESCRIPTION_TOP)
    ->widget(CKEditor::class,['editorOptions' => ['preset' => 'full']])
    ->label('Описание сверху &nbsp; &nbsp;' . Html::activeCheckbox($model, SettingsManager::NEWS_DESCRIPTION_TOP_ON)) ?>
<?= $form->field($model, SettingsManager::NEWS_DESCRIPTION_BOTTOM)
    ->widget(CKEditor::class,['editorOptions' => ['preset' => 'full']])
    ->label('Описание снизу &nbsp; &nbsp;' . Html::activeCheckbox($model, SettingsManager::NEWS_DESCRIPTION_BOTTOM_ON)) ?>
