<?php
use core\helpers\TextHelper;

/* @var $message \core\entities\SupportDialog\SupportMessage */

?>
<div class="message-row pull-<?= !$message->user_id ? 'right' : 'left' ?>">
    <div class="message-from">
        <?= !$message->user_id ? 'Служба поддержки' : $message->user->getVisibleName() ?>
        <span class="message-date"><?= Yii::$app->formatter->asDatetime($message->created_at) ?></span>
    </div>
    <div class="message-text">
        <?= TextHelper::out($message->text, 'site', false, false) ?>
    </div>
</div>
