<?php
use core\entities\User\User;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this \yii\web\View */
/* @var $model \core\forms\auth\SignupForm */

$this->title = "Регистрация";

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

            <div class="col-md-10 col-md-offset-1">
                <h1>Регистрация</h1>
                <div class="alert alert-info alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <b>Важно!</b> Тип профиля <b>"Компания"</b> - только для компаний, занимающихся продажей, производством, обслуживанием и прочими услугами в сегменте медицинского оборудования и товаров.
                </div>

                <?= $form->field($model, 'type')->dropDownList(User::getTypesArray()) ?>

                <?= $form->field($model, 'email')->input('email', ['placeholder' => 'Введите E-mail']) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'repeatPassword')->passwordInput() ?>

                <?= $form->field($model, 'captcha')->widget(Captcha::class, [
                    'captchaAction' => ['/auth/auth/captcha'],
                    'imageOptions' => ['style' => 'cursor:pointer;', 'title' => 'Обновить картинку'],
                    'options' => ['placeholder' => 'Введите код с картинки', 'class' => 'form-control'],
                    'template' => '<label class="label col-md-3">{image}</label><div class="col-md-9 no-padding">{input}</div>',

                ])->label('Проверочный код') ?>

            </div>

            <div class="col-md-12"><button type="submit" class="btn btn-lg center-block cart-btn-order"><i class="glyphicon glyphicon-ok btn-md"></i> Зарегистрироваться</button></div>
            <div class="col-xs-offset-2 col-xs-10 private-note">
                <div class="checkbox">
                    <?= $form->field($model, 'agreement')
                        ->checkbox()
                        ->label('Подтверждаю свое согласие на обработку персональных данных в соответствии с <a href="/pages/terms-of-use" title="Пользовательское соглашение" target="_blank">пользовательским соглашением</a> и <a href="/pages/privacy-policy" title="Политика конфиденциальности" target="_blank">политикой конфиденциальности</a>') ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>





    <!-- right col -->
    <div class="col-md-3 col-sm-3 hidden-xs">
        <div id="rightCol">
            sidebar right
        </div><!-- // right col -->
    </div>
</div>
