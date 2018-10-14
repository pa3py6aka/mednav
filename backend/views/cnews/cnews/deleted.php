<?php

use yii\helpers\Html;
use yii\grid\GridView;
use core\grid\ModeratorActionColumn;
use core\entities\CNews\CNews;
use core\helpers\PaginationHelper;
use yii\grid\CheckboxColumn;
use core\helpers\HtmlHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\CNewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Новости компаний';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="board-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Добавить новость', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        <?= HtmlHelper::actionButtonForSelected('Разместить выбранные', 'publish', 'primary') ?>
        <?= HtmlHelper::actionButtonForSelected('Удалить выбранные', 'remove', 'danger') ?>

        <div class="box-tools">
            <?= PaginationHelper::pageSizeSelector($dataProvider->pagination) ?>
        </div>
    </div>
    <div class="box-body table-responsive">
        <?= $this->render('_tabs', ['tab' => 'deleted']) ?>

        <?= GridView::widget([
            'id' => 'grid',
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => CheckboxColumn::class],
                [
                    'attribute' => 'name',
                    'value' => function (CNews $news) {
                        return Html::a(Html::encode($news->name), ['/cnews/cnews/view', 'id' => $news->id]);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'user_id',
                    'label' => 'Пользователь',
                    'value' => function (CNews $news) {
                        return $news->user_id . ' ' . Html::a($news->user->getVisibleName(), ['/user/view', 'id' => $news->user_id]);
                    },
                    'format' => 'raw',
                ],
                'created_at:datetime:Добавлена',
                ['class' => ModeratorActionColumn::class],
            ],
        ]); ?>
    </div>
    <div class="box-footer">
        <?= HtmlHelper::actionButtonForSelected('Разместить выбранные', 'publish', 'primary') ?>
        <?= HtmlHelper::actionButtonForSelected('Удалить выбранные', 'remove', 'danger') ?>
    </div>
</div>
