<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\forms\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'for_company_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'delivery_id') ?>

    <?= $form->field($model, 'comment') ?>

    <?php // echo $form->field($model, 'user_name') ?>

    <?php // echo $form->field($model, 'user_phone') ?>

    <?php // echo $form->field($model, 'user_email') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
