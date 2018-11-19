<?php
use yii\helpers\ArrayHelper;
use core\entities\Dialog\Dialog;
use yii\helpers\Html;
use core\helpers\PaginationHelper;
use core\helpers\OrderHelper;

/* @var $this yii\web\View */
/* @var $provider \yii\data\ActiveDataProvider */

/* @var $tab string */

$this->title = 'Личный кабинет | Мои сообщения';
$tab =  ArrayHelper::getValue($this->params, 'tab', 'active');

?>
<div class="row">
    <div class="col-md-3">
        <?= $this->render('@frontend/views/user/_left_menu', ['user' => $this->params['user']]) ?>
    </div>

    <div class="col-md-9">
        <?= \frontend\widgets\AccountBreadcrumbs::show(['Сообщения']) ?>
        <h1>
            Сообщения
            <span class="pull-right">
                <?= PaginationHelper::pageSizeSelector($provider->pagination, PaginationHelper::SITE_SIZES); ?>
            </span>
        </h1>

        <?= \yii\grid\GridView::widget([
            'dataProvider' => $provider,
            'layout' => "{items}\n{summary}\n{pager}",
            'id' => 'grid',
            'columns' => [
                [
                    'label' => 'Тема',
                    'value' => function (Dialog $dialog) {
                        return Html::a(Html::encode($dialog->subject), ['view', 'id' => $dialog->id]) .
                            ($dialog->not_read ? ' <span class="label label-danger label-as-badge">' . $dialog->not_read . '</span>' : '');
                    },
                    'format' => 'raw',
                ],
                [
                    'label' => 'Контакт',
                    'value' => function (Dialog $dialog) {
                        return ($contact = $dialog->getInterlocutor(Yii::$app->user->id)) ?
                            Html::a($contact->getVisibleName(), $contact->getUrl())
                            : "Посетитель сайта";
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'date',
                    'label' => 'Дата',
                    'value' => function (Dialog $dialog) {
                        return $dialog->lastMessage->created_at;
                    },
                    'format' => 'datetime',
                ],
                ['class' => \yii\grid\ActionColumn::class, 'template' => '{view}']
            ],
        ]) ?>
    </div>
</div>
