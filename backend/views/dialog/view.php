<?php
use yii\helpers\Html;
use core\entities\SupportDialog\SupportDialog;
use core\entities\SupportDialog\SupportMessage;
use backend\assets\AppAsset;

/* @var $this yii\web\View */
/* @var $provider \yii\data\ActiveDataProvider */
/* @var $dialog SupportDialog */
/* @var $user \core\entities\User\User */

/* @var $message SupportMessage */

$this->title = 'Сообщения';
$this->registerJsFile(Yii::$app->params['frontendHostInfo'] . '/js/chat.js?v=' . filemtime(Yii::getAlias('@frontend/web/js/chat.js')), ['depends' => AppAsset::class]);

?>
<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">[Служба поддержки] <?= Html::encode($dialog->subject) ?></h3>
    </div>
    <div class="box-body message-block">
        <?php if ($dialog->text): ?>
            <div class="dialog_text">
                <?= Yii::$app->formatter->asNtext($dialog->text) ?>
            </div>
        <?php endif; ?>
        <?php foreach ($provider->models as $message): ?>
            <?= $this->render('_message-row', ['message' => $message]) ?>
        <?php endforeach; ?>
    </div>
    <div class="box-footer">
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