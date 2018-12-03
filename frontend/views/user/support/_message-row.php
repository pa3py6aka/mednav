<?php

use core\helpers\TextHelper;
use core\helpers\DialogHelper;

/* @var $message \core\entities\SupportDialog\SupportMessage */

?>
<div class="message-row pull-<?= $message->isMy() ? 'right' : 'left' ?>">
    <div class="message-from<?= !$message->isMy() ? ' text-green' : '' ?>">
        <?= DialogHelper::getSupportUserName($message) ?>
        <span class="message-date"><?= Yii::$app->formatter->asDatetime($message->created_at) ?></span>
    </div>
    <div class="message-text">
        <?= TextHelper::out($message->text, 'site') ?>
    </div>
</div>
