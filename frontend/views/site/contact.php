<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \core\forms\ContactForm */

use yii\bootstrap\ActiveForm;

$this->title = 'Обратная связь';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-9 col-sm-9 col-xs-12">
        <div class="row">

            <?php $form = ActiveForm::begin([
                'layout' => 'horizontal',
                'enableClientValidation' => true,
                'fieldConfig' => [
                    'horizontalCssClasses' => [
                        'label' => 'col-md-2',
                        'wrapper' => 'col-md-10',
                        'offset' => '',
                    ]
                ]
            ]); ?>

            <div class="col-md-8 col-md-offset-2">
                <h1>Написать нам</h1>

                <?= $form->field($model, 'name')->input('text') ?>
                <?= $form->field($model, 'email')->textInput() ?>
                <?= $form->field($model, 'phone')->textInput() ?>
                <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>
                <?php /*= $form->field($model, 'captcha')->widget(Captcha::class, [
                    'captchaAction' => ['/auth/auth/captcha'],
                    'imageOptions' => ['style' => 'cursor:pointer;', 'title' => 'Обновить картинку'],
                    'options' => ['placeholder' => 'Введите код с картинки', 'class' => 'form-control'],
                    'template' => '<label class="label col-md-3">{image}</label><div class="col-md-9 no-padding">{input}</div>',
                ])->label('Проверочный код')*/ ?>
                <?= $form->field($model, 'captcha')->widget(\himiklab\yii2\recaptcha\ReCaptcha::class)->label('') ?>

            </div>

            <div class="col-xs-offset-3 col-xs-9 private-note">
                <div class="checkbox">
                    <?= $form->field($model, 'agreement')
                        ->checkbox()
                        ->label('Подтверждаю свое согласие на обработку персональных данных в соответствии с <a href="/pages/terms-of-use" title="Пользовательское соглашение" target="_blank">пользовательским соглашением</a> и <a href="/pages/privacy-policy" title="Политика конфиденциальности" target="_blank">политикой конфиденциальности</a>') ?>
                </div>
            </div>

            <div class="col-md-12">
                <button type="submit" class="btn btn-lg center-block cart-btn-order"><i class="glyphicon glyphicon-ok btn-md"></i> Отправить</button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <!-- right col -->
    <div class="col-md-3 col-sm-3 hidden-xs">
        <div id="rightCol">

        </div><!-- // right col -->
    </div>
</div>
