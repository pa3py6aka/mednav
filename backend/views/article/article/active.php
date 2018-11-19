<?php

use yii\helpers\Html;
use yii\grid\GridView;
use core\entities\Article\Article;
use yii\grid\ActionColumn;
use core\helpers\PaginationHelper;
use yii\grid\CheckboxColumn;
use core\helpers\HtmlHelper;
use core\components\Settings;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::$app->settings->get(Settings::ARTICLE_TITLE) ?: 'Статьи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="board-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Добавить статью', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
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
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => CheckboxColumn::class],
                [
                    'attribute' => 'name',
                    'value' => function (Article $article) {
                        return '<div style="width:400px;">' . Html::a(Html::encode($article->name), ['/article/article/view', 'id' => $article->id]) . '</div>';
                    },
                    'contentOptions' => ['style' => 'white-space:normal;'],
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'user_id',
                    'label' => 'Пользователь',
                    'value' => function (Article $article) {
                        return $article->user_id . ' ' . Html::a($article->user->getVisibleName(), ['/user/view', 'id' => $article->user_id]);
                    },
                    'format' => 'raw',
                ],
                ['class' => \core\grid\UserProfileColumn::class],
                ['class' => \core\grid\CategoryColumn::class, 'url' => ['/article/category/update', 'id' => '{id}']],
                'created_at:datetime:Добавлена',
                ['class' => ActionColumn::class, 'template' => '{update} {delete}'],
            ],
        ]); ?>
    </div>
    <div class="box-footer">
        <?= HtmlHelper::actionButtonForSelected('Удалить выбранные', 'remove', 'danger') ?>
    </div>
</div>
