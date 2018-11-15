<?php
use core\entities\SupportDialog\SupportDialog;
use yii\helpers\Html;
use core\helpers\PaginationHelper;

/* @var $this yii\web\View */
/* @var $provider \yii\data\ActiveDataProvider */

$this->title = 'Личный кабинет | Служба поддержки';

?>
<div class="row">
    <div class="col-md-3">
        <?= $this->render('@frontend/views/user/_left_menu', ['user' => $this->params['user']]) ?>
    </div>

    <div class="col-md-9">
        <?= \frontend\widgets\AccountBreadcrumbs::show(['Служба поддержки']) ?>
        <h1>
            Служба поддержки
            <span class="pull-right">
                <?= PaginationHelper::pageSizeSelector($provider->pagination, PaginationHelper::SITE_SIZES); ?>
            </span>
        </h1>
        <a href="<?= \yii\helpers\Url::to(['/user/support/create']) ?>" class="btn btn-success">Написать сообщение</a><br><br>

        <?= \yii\grid\GridView::widget([
            'dataProvider' => $provider,
            'layout' => "{items}\n{summary}\n{pager}",
            'id' => 'grid',
            'columns' => [
                [
                    'label' => 'Тема',
                    'value' => function (SupportDialog $dialog) {
                        return Html::a(Html::encode($dialog->subject), ['view', 'id' => $dialog->id]) .
                            ($dialog->not_read ? ' <span class="label label-danger label-as-badge">' . $dialog->not_read . '</span>' : '');
                    },
                    'format' => 'raw',
                ],
                [
                    'label' => 'Дата',
                    'value' => function (SupportDialog $dialog) {
                        return $dialog->lastMessage->created_at;
                    },
                    'format' => 'datetime',
                ],
                ['class' => \yii\grid\ActionColumn::class, 'template' => '{view}']
            ],
        ]) ?>
    </div>
</div>
