<?php
use yii\bootstrap\ActiveForm;

/* @var $this \yii\web\View */
/* @var $model \core\forms\auth\LoginForm */

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

            <div class="col-md-8 col-md-offset-2">
                <h1>Вход</h1>

                <?= $form->field($model, 'email')->input('email', ['placeholder' => 'Введите E-mail']) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <div class="form-group">
                    <label for="loginform-rememberme" class="control-label col-md-2"></label>
                    <div class="col-md-10">
                        <?= \yii\bootstrap\Html::activeCheckbox($model, 'rememberMe') ?> <span style="float: right"> <a href="<?= \yii\helpers\Url::to(['/auth/reset/request']) ?>">Забыли пароль?</a></span>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <button type="submit" class="btn btn-lg center-block cart-btn-order"><i class="glyphicon glyphicon-ok btn-md"></i> Войти</button>
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
