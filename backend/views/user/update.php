<?php

/* @var $this yii\web\View */
/* @var $model \core\forms\manage\User\UserCreateForm */

$this->title = 'Редактирование';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="user-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
