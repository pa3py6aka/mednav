<?php

use frontend\widgets\message\MessageWidget;

/* @var $company \core\entities\Company\Company */

?>
<div class="kk-add-lnk hidden-xs">
    <?= MessageWidget::widget([
        'toUser' => $company->user,
        'subjectType' => MessageWidget::SUBJECT_TYPE_INPUT,
        'buttonType' => MessageWidget::BTN_TYPE_SMALL,
    ]) ?>
    <span class="glyphicon glyphicon-user btn-xs icon-blue"></span>
    <a href="#">Добавить в контакты</a>
</div>
