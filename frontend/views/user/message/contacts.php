<?php
use core\entities\Contact;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $provider \yii\data\ActiveDataProvider */

$this->title = 'Личный кабинет | Контакты';

?>
<div>
    <?= \yii\grid\GridView::widget([
        'dataProvider' => $provider,
        'layout' => "{items}\n{summary}\n{pager}",
        'id' => 'grid',
        'columns' => [
            [
                'label' => 'Компания',
                'value' => function (Contact $contact) {
                    return Html::a($contact->contact->getVisibleName(), $contact->contact->getUrl());
                },
                'format' => 'raw',
            ],
            [
                'class' => \yii\grid\ActionColumn::class,
                'template' => '{message} {delete}',
                'buttons' => [
                    'message' => function ($url, $model, $key) {
                        return '<a href="' . Url::to(['contact', 'id' => $model->id]) . '"><i class="glyphicon glyphicon-envelope"></i></a>';
                    },
                    'delete' => function ($url, $model, $key) {
                        return '<a href="' . Url::to(['contact-remove', 'id' => $model->id]) . '" data-method="post" data-confirm="Удалить данный контакт?"><i class="glyphicon glyphicon-trash"></i></a>';
                    },
                ],
            ]
        ],
    ]) ?>
</div>
