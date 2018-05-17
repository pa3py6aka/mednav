<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use core\entities\User\User;
use frontend\widgets\RegionsModalWidget;

/* @var $this yii\web\View */
/* @var $user \core\entities\User\User int */
/* @var $profileModel \core\forms\User\UserProfileForm */
/* @var $passwordModel \core\forms\User\UpdatePasswordForm */
/* @var $tab string */


$this->title = 'Личный кабинет | Данные профиля';


?>
<div class="row">
    <div class="col-md-3">
        <?= $this->render('@frontend/views/user/_left_menu', ['user' => $user]) ?>
    </div>
    <div class="col-md-9">
        <?= \frontend\widgets\AccountBreadcrumbs::show(['Мои данные']) ?>
        <h1>Мои данные</h1>

        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"<?= $tab == 'main' ? ' class="active"' : '' ?>><a href="#main" data-toggle="tab" aria-controls="main">Основные данные</a></li>
            <li role="presentation"<?= $tab == 'type' ? ' class="active"' : '' ?>><a href="#type" data-toggle="tab" aria-controls="type">Тип профиля</a></li>
            <li role="presentation"<?= $tab == 'access' ? ' class="active"' : '' ?>><a href="#access" data-toggle="tab" aria-controls="access">Доступ</a></li>
        </ul>
        <br>
        <div class="tab-content">
            <!-- Основные данные -->
            <div class="tab-pane fade<?= $tab == 'main' ? ' active in' : '' ?>" role="tabpanel" id="main" aria-labelledby="main-tab">
                <?php $form = ActiveForm::begin() ?>

                <?= $form->field($profileModel, 'last_name')->textInput() ?>

                <?= $form->field($profileModel, 'name')->textInput() ?>

                <?= $form->field($profileModel, 'patronymic')->textInput() ?>

                <?php if (!$user->isCompany()) {
                    echo $form->field($profileModel, 'geoId')
                        ->hiddenInput(['id' => 'form-geo-id'])
                        ->hint('<span class="region-select-link" data-toggle="modal" data-target="#modalRegion">' . $profileModel->geoName() . '</span>');
                } ?>

                <div class="col-xs-4 no-padding">
                    <?= $form->field($profileModel, 'gender')->dropDownList(User::gendersArray()) ?>
                </div>

                <div class="col-xs-8" style="padding-right:0">
                    <?= $form->field($profileModel, 'birthday')->widget(\kartik\date\DatePicker::class, [
                        'options' => ['placeholder' => 'Укажите дату рождения'],
                        'pluginOptions' => [
                            'format' => 'dd.mm.yyyy',
                            'autoclose' => true,
                        ]
                    ]) ?>
                </div>

                <?= $form->field($profileModel, 'phone')->textInput() ?>

                <?= $form->field($profileModel, 'organization')->textInput() ?>

                <?= $form->field($profileModel, 'email')->input('email') ?>

                <?= $form->field($profileModel, 'site')->textInput() ?>

                <?= $form->field($profileModel, 'skype')->textInput() ?>

                <button type="submit" class="btn btn-primary">Сохранить</button>
                <?php ActiveForm::end() ?>
            </div>

            <!-- Тип профиля -->
            <div class="tab-pane fade<?= $tab == 'type' ? ' active in' : '' ?>" role="tabpanel" id="type" aria-labelledby="type-tab">
                <?= Html::beginForm() ?>
                Ваш профиль:
                <?= Html::dropDownList('userType', $user->type, User::getTypesArray(), ['class' => 'form-control', 'style' => 'width:max-content;display:inline-block;']) ?>
                <button type="submit" class="btn btn-primary btn-sm">Сохранить</button>
                <?= Html::endForm() ?>
            </div>

            <!-- Доступ -->
            <div class="tab-pane fade<?= $tab == 'access' ? ' active in' : '' ?>" role="tabpanel" id="access" aria-labelledby="access-tab">
                <?php $form = ActiveForm::begin(['action' => ['profile', 'tab' => 'access']]) ?>

                <?= $form->field($passwordModel, 'oldPassword')->passwordInput() ?>

                <?= $form->field($passwordModel, 'password')->passwordInput() ?>

                <?= $form->field($passwordModel, 'repeatPassword')->passwordInput() ?>

                <button type="submit" class="btn btn-primary">Сохранить</button>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>

<?= RegionsModalWidget::widget() ?>
