<?php

use frontend\widgets\message\MessageWidget;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $buttonType int */
/* @var $subjectType string */
/* @var $toName string */
/* @var $btnClass string */
/* @var $btnWidth string */
/* @var $model \frontend\widgets\message\NewMessageForm */

?>
<?php if ($buttonType == MessageWidget::BTN_TYPE_BIG): ?>
    <a href="#ModalMsg" class="form-control btn <?= $btnClass ?>" data-toggle="modal" style="width:<?= $btnWidth ?>;">
        <span class="glyphicon glyphicon-envelope btn-xs"></span> Написать сообщение
    </a>
<?php else: ?>
    <span class="glyphicon glyphicon-envelope btn-xs icon-blue"></span>
    <a href="#ModalMsg" data-toggle="modal">Написать сообщение</a>
<?php endif; ?>

<!--modal message-->
<div id="ModalMsg" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content has-overlay">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4>Сообщение для <?= $toName ?></h4>
            </div>

            <div class="modal-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'message-form',
                ]) ?>
                    <?= $form->field($model, 'toId')->hiddenInput()->label(false) ?>

                    <?= $subjectType == MessageWidget::SUBJECT_TYPE_SOLID ?
                        $form->field($model, 'subject')->hiddenInput()->label(false)
                        :$form->field($model, 'subject')->textInput(['placeholder' => 'Тема*'])->label(false)  ?>

                    <?php if ($model->subject): ?>
                        <div class="form-group">
                            <b>Тема:</b> <?= $model->subject ?>
                        </div>
                    <?php endif; ?>

                    <?= $form->field($model, 'text')->textarea(['placeholder' => 'Текст сообщения*', 'rows' => 3])->label(false) ?>

                    <?php if (Yii::$app->user->isGuest): ?>
                        <div class="form-inline">
                            <?= $form->field($model, 'name')->textInput(['placeholder' => 'Имя*'])->label(false) ?>

                            <?= $form->field($model, 'email')->input('email', ['placeholder' => 'Email*'])->label(false) ?>

                            <?= $form->field($model, 'phone')->textInput(['placeholder' => 'Телефон'])->label(false) ?>
                        </div>
                        <br>
                        <?php /*= $form->field($model, 'captcha')->widget(Captcha::class, [
                            'captchaAction' => ['/auth/auth/captcha'],
                            'imageOptions' => ['style' => 'cursor:pointer;', 'title' => 'Обновить картинку'],
                            'options' => ['placeholder' => 'Введите код с картинки', 'class' => 'form-control'],
                            'template' => '<label class="label col-md-3">{image}</label><div class="col-md-9 no-padding">{input}</div>',
                        ])->label('')*/ ?>
                        <?= $form->field($model, 'captcha')->widget(\himiklab\yii2\recaptcha\ReCaptcha::class)->label('') ?>

                        <div class="checkbox">
                            <?= $form->field($model, 'agreement')
                                ->checkbox()
                                ->label('Подтверждаю свое согласие на обработку персональных данных в соответствии с <a href="/pages/terms-of-use" title="Пользовательское соглашение" target="_blank">пользовательским соглашением</a> и <a href="/pages/privacy-policy" title="Политика конфиденциальности" target="_blank">политикой конфиденциальности</a>') ?>
                        </div>

                    <?php endif; ?>
                <?php ActiveForm::end() ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary send-message-form-button">Отправить сообщение</button>
            </div>
        </div>
    </div>
</div>
<!-- // modal message-->
