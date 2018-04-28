<?php

use core\entities\User\User;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Добавить пользователя', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                'id',
                'email:email',
                [
                    'attribute' => 'type',
                    'value' => function(User $user) {
                        return $user->typeName;
                    },
                    'filter' => User::getTypesArray(),
                ],
                [
                    'attribute' => 'created_at',
                    'format' => 'date'
                ],
                [
                    'attribute' => 'status',
                    'value' => function(User $user) {
                        return $user->statusName;
                    },
                    'filter' => User::getStatusesArray(),
                ],
                // 'auth_key',
                // 'password_hash',
                // 'password_reset_token',
                // 'created_at',
                // 'updated_at',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
