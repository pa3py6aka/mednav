<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model core\entities\Order\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'id')->textInput() ?>

        <?= $form->field($model, 'for_company_id')->textInput() ?>

        <?= $form->field($model, 'user_id')->textInput() ?>

        <?= $form->field($model, 'delivery_id')->textInput() ?>

        <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'user_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'user_phone')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'user_email')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'status')->textInput() ?>

        <?= $form->field($model, 'created_at')->textInput() ?>

        <?= $form->field($model, 'updated_at')->textInput() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
