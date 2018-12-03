<?php
use frontend\widgets\ContactButtonWidget\ContactButtonWidget;
use frontend\widgets\message\MessageWidget;

/* @var $company \core\entities\Company\Company */

?>
<div class="kk-add-lnk hidden-xs">
    <?= MessageWidget::widget([
        'toUser' => $company->user,
        'subjectType' => MessageWidget::SUBJECT_TYPE_INPUT,
        'buttonType' => MessageWidget::BTN_TYPE_SMALL,
    ]) ?>
    <?= ContactButtonWidget::widget([
        'buttonType' => ContactButtonWidget::BUTTON_SMALL,
        'contactId' => $company->user_id,
    ]) ?>
</div>
