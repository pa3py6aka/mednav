<?php

use yii\helpers\Html;
use core\entities\Dialog\Dialog;
use core\entities\Dialog\Message;
use frontend\assets\AppAsset;

/* @var $this yii\web\View */
/* @var $provider \yii\data\ActiveDataProvider */
/* @var $dialog Dialog */
/* @var $user \core\entities\User\User */

/* @var $message Message */

$this->title = 'Личный кабинет | Переписка';
$this->registerJsFile('@web/js/chat.js?v=' . filemtime(Yii::getAlias('@webroot/js/chat.js')), ['depends' => AppAsset::class]);

?>
<div class="row">
    <div class="col-md-3">
        <?= $this->render('@frontend/views/user/_left_menu', ['user' => $this->params['user']]) ?>
    </div>

    <div class="col-md-9">
        <?= \frontend\widgets\AccountBreadcrumbs::show([
            ['label' => 'Сообщения', 'url' => ['/user/message/dialogs']],
            $dialog->getDialogName()
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

        <?php if ($dialog->getInterlocutorId($user->id)): ?>
            <?php if (!$dialog->getInterlocutor($user->id)->isActive()): ?>
                <div class="alert alert-danger">
                    Пользователь удалён или заблокирован, отправка сообщений невозможна.
                </div>
            <?php else: ?>
                <div class="form-group">
                    <textarea name="message" class="form-control" rows="3" title="Сообщение" placeholder="Введите сообщение..."></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-info">Отправить</button>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="chat-user-details">
                <b>Имя:</b> <?= Html::encode($dialog->name) ?><br>
                <b>Телефон:</b> <?= Html::encode($dialog->phone) ?><br>
                <b>E-mail:</b> <?= Html::a(Html::encode($dialog->email), 'mailto:' . Html::encode($dialog->email)) ?><br>
            </div>
        <?php endif; ?>

        <?= Html::endForm() ?>
    </div>
</div>
