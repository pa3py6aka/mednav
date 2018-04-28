<?php
use yii\bootstrap\ActiveForm;

/* @var $this \yii\web\View */
/* @var $model \core\forms\auth\ResetPasswordForm */

$this->title = "Сброс пароля";

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
                <h1>Сброс пароля</h1>

                <?= $form->field($model, 'password')->input('password', ['placeholder' => 'Введите новый пароль']) ?>

            </div>

            <div class="col-md-12">
                <button type="submit" class="btn btn-lg center-block cart-btn-order"><i class="glyphicon glyphicon-ok btn-md"></i> Сохранить</button>
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
