<?php

use yii\grid\ActionColumn;
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
        <?= HtmlHelper::actionButtonForSelected('Удалить выбранных', 'remove', 'danger') ?>

        <div class="box-tools">
            <?= PaginationHelper::pageSizeSelector($dataProvider->pagination) ?>
        </div>
    </div>
    <div class="box-body table-responsive">
        <?= $this->render('_tabs', ['tab' => 'active']) ?>

        <?= GridView::widget([
            'id' => 'grid',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => \yii\grid\CheckboxColumn::class],
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
                    'format' => 'date',
                    /*'filter' => DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'created_at',
                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        //'separator' => '-',
                        'pickerButton' => false,
                        'pluginOptions' => [
                            'todayHighlight' => true,
                            'autoclose' => true,
                            'format' => 'dd.mm.yyyy',
                        ],
                    ])*/
                    'filter' => \yii\widgets\MaskedInput::widget([
                        'model' => $searchModel,
                        'attribute' => 'created_at',
                        'mask' => '99.99.9999',
                    ])
                ],
                [
                    'attribute' => 'status',
                    'value' => function(User $user) {
                        return $user->statusName;
                    },
                    'filter' => User::getStatusesArray(true),
                ],
                [
                    'attribute' => 'lastOnline',
                    'label' => 'Был на сайте',
                    'value' => function(User $user) {
                        return $user->last_online ? Yii::$app->formatter->asDatetime($user->last_online) : '-';
                    },
                    'format' => 'raw'
                ],

                ['class' => ActionColumn::class],
            ],
        ]); ?>
    </div>
    <div class="box-footer">
        <?= HtmlHelper::actionButtonForSelected('Удалить выбранных', 'remove', 'danger') ?>
    </div>
</div>
