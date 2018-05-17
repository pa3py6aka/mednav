<?php


use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $user \core\entities\User\User int */


$this->title = 'Личный кабинет | Моя компания';


?>
<div class="row">
    <div class="col-md-3">
        <?= $this->render('@frontend/views/user/_left_menu', ['user' => $user]) ?>
    </div>

    <div class="col-md-9">
        <?= \frontend\widgets\AccountBreadcrumbs::show(['Моя компания']) ?>
        <h1>Моя компания</h1>

        <?php $form = ActiveForm::begin() ?>



        <button type="submit" class="btn btn-primary">Сохранить</button>
        <?php ActiveForm::end() ?>
    </div>
</div>
