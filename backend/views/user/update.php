<?php

use core\entities\User\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\widgets\RegionsModalWidget;

/* @var $this yii\web\View */
/* @var $model \core\forms\manage\User\UserEditForm */

$this->title = 'Редактирование';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="user-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'password')
            ->passwordInput(['maxlength' => true])
            ->hint('Если поле оставить пустым, пароль не будет изменён.') ?>

        <?= $form->field($model, 'type')->dropDownList(User::getTypesArray()) ?>

        <?= $form->field($model, 'role')->dropDownList(ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description')) ?>

        <?= $form->field($model, 'lastName')->textInput() ?>

        <?= $form->field($model, 'name')->textInput() ?>

        <?= $form->field($model, 'patronymic')->textInput() ?>

        <?php if (!$model->_user->isCompany()) {
            echo $form->field($model, 'geoId')
                ->hiddenInput(['id' => 'form-geo-id'])
                ->hint('<span class="input-modal-link" id="region-select-link" data-toggle="modal" data-target="#modalRegion">' . $model->geoName() . '</span>');
        } ?>

        <div class="col-xs-4 no-padding">
            <?= $form->field($model, 'gender')->dropDownList(User::gendersArray()) ?>
        </div>

        <div class="col-xs-8" style="padding-right:0">
            <?= $form->field($model, 'birthday')->widget(\yii\widgets\MaskedInput::class, [
                'mask' => '99.99.9999',
                'clientOptions' => ['placeholder' => 'дд.мм.гггг']
            ]) ?>
        </div>

        <?= $form->field($model, 'phone')->textInput() ?>

        <?= $form->field($model, 'organization')->textInput() ?>

        <?= $form->field($model, 'site')->textInput() ?>

        <?= $form->field($model, 'skype')->textInput() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?= RegionsModalWidget::widget() ?>
