<?php

use core\grid\ModeratorActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use core\entities\User\User;
use core\helpers\PaginationHelper;
use core\helpers\HtmlHelper;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="board-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Добавить пользователя', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        <?= HtmlHelper::actionButtonForSelected('Активировать выбранных', 'publish', 'primary') ?>
        <?= HtmlHelper::actionButtonForSelected('Удалить выбранных', 'remove', 'danger') ?>

        <div class="box-tools">
            <?= PaginationHelper::pageSizeSelector($dataProvider->pagination) ?>
        </div>
    </div>
    <div class="box-body table-responsive">
        <?= $this->render('_tabs', ['tab' => 'moderation']) ?>

        <?= GridView::widget([
            'id' => 'grid',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => \yii\grid\CheckboxColumn::class],
                [
                    'attribute' => 'id',
                    'value' => function(User $user) {
                        return Html::a($user->id, ['view', 'id' => $user->id]);
                    },
                    'format' => 'raw',
                ],
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
                    'format' => 'date',
                    'filter' => \yii\widgets\MaskedInput::widget([
                        'model' => $searchModel,
                        'attribute' => 'created_at',
                        'mask' => '99.99.9999',
                    ])
                ],

                ['class' => ModeratorActionColumn::class],
            ],
        ]); ?>
    </div>
    <div class="box-footer">
        <?= HtmlHelper::actionButtonForSelected('Удалить выбранных', 'remove', 'danger') ?>
    </div>
</div>
