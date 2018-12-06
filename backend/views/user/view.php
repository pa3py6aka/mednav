<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;
use core\entities\User\User;

/* @var $this yii\web\View */
/* @var $model core\entities\User\User */

$tab = in_array($model->status, [User::STATUS_ACTIVE, User::STATUS_WAIT], true) ? 'active' : ($model->status === User::STATUS_DELETED ? 'deleted' : 'moferation');
$this->title = $model->typeName .' #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => [$tab]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view box box-primary">
    <div class="box-header">
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Сообщение', ['message', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?php if ($model->isOnModeration() || $model->isDeleted()) {
            echo Html::a('Разместить', ['confirm', 'id' => $model->id], ['class' => 'btn btn-success btn-flat']);
        } ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => 'Вы уверены?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'email:email',
                'typeName',
                'statusName',
                [
                    'label' => 'Роль',
                    'value' => implode(', ', ArrayHelper::getColumn(Yii::$app->authManager->getRolesByUser($model->id), 'description')),
                    'format' => 'raw',
                ],
                //'auth_key',
                //'password_hash',
                //'password_reset_token',
                'created_at:datetime',
                'updated_at:datetime',
                'last_online:datetime',
            ],
        ]) ?>
    </div>
</div>
