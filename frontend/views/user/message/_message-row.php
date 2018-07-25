<?php

use core\helpers\TextHelper;
use core\helpers\DialogHelper;

/* @var $message \core\entities\Dialog\Message */

?>
<div class="message-row pull-<?= $message->isMy() ? 'right' : 'left' ?>">
    <div class="message-from">
        <?= DialogHelper::getUserName($message) ?>
        <span class="message-date"><?= Yii::$app->formatter->asDatetime($message->created_at) ?></span>
    </div>
    <div class="message-text">
        <?= TextHelper::out($message->text) ?>
    </div>
</div>
