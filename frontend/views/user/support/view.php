<?php

use yii\helpers\Html;
use core\entities\SupportDialog\SupportDialog;
use core\entities\SupportDialog\SupportMessage;
use frontend\assets\AppAsset;

/* @var $this yii\web\View */
/* @var $provider \yii\data\ActiveDataProvider */
/* @var $dialog SupportDialog */
/* @var $user \core\entities\User\User */

/* @var $message SupportMessage */

$this->title = 'Личный кабинет | Служба поддержки';
$this->registerJsFile('@web/js/chat.js?v=' . filemtime(Yii::getAlias('@webroot/js/chat.js')), ['depends' => AppAsset::class]);

?>
<div class="row">
    <div class="col-md-3">
        <?= $this->render('@frontend/views/user/_left_menu', ['user' => $this->params['user']]) ?>
    </div>

    <div class="col-md-9">
        <?= \frontend\widgets\AccountBreadcrumbs::show([
            ['label' => 'Служба поддержки', 'url' => ['/user/support/dialogs']],
            Html::encode($dialog->subject)
        ]) ?>
        <h1>
            Тема: <?= Html::encode($dialog->subject) ?>
        </h1>
        <?php if ($dialog->text): ?>
            <div class="dialog_text">
                <?= Yii::$app->formatter->asNtext($dialog->text) ?>
            </div>
        <?php endif; ?>
        <div class="message-block container-fluid">
            <?php foreach ($provider->models as $message): ?>
                <?= $this->render('_message-row', ['message' => $message]) ?>
            <?php endforeach; ?>
        </div>
        <?= Html::beginForm('', 'post', ['id' => 'chat-message-form', 'class' => 'has-overlay']) ?>

        <div class="form-group">
            <textarea name="message" class="form-control" rows="3" title="Сообщение" placeholder="Введите сообщение..."></textarea>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-info">Отправить</button>
        </div>

        <?= Html::endForm() ?>
    </div>
</div>
