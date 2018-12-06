<?php
use core\entities\Dialog\Dialog;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $provider \yii\data\ActiveDataProvider */

$this->title = 'Личный кабинет | Мои сообщения';

?>
<div>
    <?= \yii\grid\GridView::widget([
        'dataProvider' => $provider,
        'layout' => "{items}\n{summary}\n{pager}",
        'id' => 'grid',
        'columns' => [
            [
                'label' => 'Тема',
                'value' => function (Dialog $dialog) {
                    return Html::a($dialog->getDialogName(), ['view', 'id' => $dialog->id]) .
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
                    //return $dialog->lastMessage ? Yii::$app->formatter->asDatetime($dialog->lastMessage->created_at) : '-';
                },
                'format' => 'datetime',
            ],
            ['class' => \yii\grid\ActionColumn::class, 'template' => '{view}']
        ],
    ]) ?>
</div>
