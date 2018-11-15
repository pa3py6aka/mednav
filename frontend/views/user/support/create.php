<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use core\helpers\PaginationHelper;

/* @var $this yii\web\View */
/* @var $model \core\forms\SupportMessageForm */

$this->title = 'Личный кабинет | Новое обращение в службу поддержки';

?>
<div class="row">
    <div class="col-md-3">
        <?= $this->render('@frontend/views/user/_left_menu', ['user' => $this->params['user']]) ?>
    </div>

    <div class="col-md-9">
        <?= \frontend\widgets\AccountBreadcrumbs::show([
            ['label' => 'Служба поддержки', 'url' => ['/user/support/dialogs']],
            'Новое сообщение'
        ]) ?>
        <h1>Новое сообщение</h1>

        <?php $form = ActiveForm::begin() ?>

        <?= $form->field($model, 'subject')
            ->textInput(['placeholder' => 'Укажите тему Вашего обращения'])
            ->label(false) ?>

        <?= $form->field($model, 'text')
            ->textarea(['rows' => 5, 'placeholder' => 'Введите текст сообщения'])
            ->label(false) ?>

        <button type="submit" class="btn btn-success">Отправить</button>

        <?php ActiveForm::end() ?>
    </div>
</div>
