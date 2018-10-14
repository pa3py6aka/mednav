<?php

use yii\helpers\Html;
use yii\grid\GridView;
use core\entities\CNews\CNews;
use yii\grid\ActionColumn;
use core\helpers\PaginationHelper;
use yii\grid\CheckboxColumn;
use core\helpers\HtmlHelper;
use core\entities\User\User;
use core\helpers\CategoryHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\CNewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Новости компаний';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="board-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Добавить новость', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
        <?= HtmlHelper::actionButtonForSelected('Удалить выбранные', 'remove', 'danger') ?>

        <div class="box-tools">
            <?= PaginationHelper::pageSizeSelector($dataProvider->pagination) ?>
        </div>
    </div>
    <div class="box-body table-responsive">
        <?= $this->render('_tabs', ['tab' => 'active']) ?>

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
                [
                    'attribute' => 'userType',
                    'label' => 'Профиль',
                    'value' => function(CNews $news) {
                        return $news->user->typeName;
                    },
                    'filter' => User::getTypesArray(),
                ],
                [
                    'attribute' => 'category_id',
                    'value' => function (CNews $news) {
                        return Html::a($news->category->name, ['/cnews/category/update', 'id' => $news->category_id], [
                            'data-toggle' => 'tooltip',
                            'title' => CategoryHelper::categoryParentsString($news->category),
                        ]);
                    },
                    'format' => 'raw',
                ],
                'created_at:datetime:Добавлена',
                ['class' => ActionColumn::class, 'template' => '{update} {delete}'],
            ],
        ]); ?>
    </div>
    <div class="box-footer">
        <?= HtmlHelper::actionButtonForSelected('Удалить выбранные', 'remove', 'danger') ?>
    </div>
</div>
