<?php
use yii\helpers\Html;
use core\entities\SupportDialog\SupportDialog;
use core\helpers\PaginationHelper;

/* @var $this yii\web\View */
/* @var $provider yii\data\ActiveDataProvider */

$this->title = 'Сообщения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-header with-border">
        <br>
        <div class="box-tools">
            <?= PaginationHelper::pageSizeSelector($provider->pagination) ?>
        </div>
    </div>
    <div class="box-body table-responsive">
        <?= \yii\grid\GridView::widget([
            'dataProvider' => $provider,
            'layout' => "{items}\n{summary}\n{pager}",
            'id' => 'grid',
            'columns' => [
                [
                    'label' => 'Тема',
                    'value' => function (SupportDialog $dialog) {
                        return Html::a('[Служба поддержки] ' . Html::encode($dialog->subject), ['view', 'id' => $dialog->id]) .
                            ($dialog->not_read ? ' <span class="label label-danger label-as-badge">' . $dialog->not_read . '</span>' : '');
                    },
                    'format' => 'raw',
                ],
                [
                    'label' => 'От кого',
                    'value' => function (SupportDialog $dialog) {
                        return Html::a(Html::encode($dialog->user->getVisibleName()), ['user/view', 'id' => $dialog->user_id]);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'date',
                    'label' => 'Дата',
                    'value' => function (SupportDialog $dialog) {
                        //return $dialog->max;
                        return $dialog->lastMessage->created_at;
                    },
                    'format' => 'datetime',
                ],
                ['class' => \yii\grid\ActionColumn::class, 'template' => '{view}']
            ],
        ]) ?>
    </div>
</div>
