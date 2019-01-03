<?php

use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use core\entities\Page;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Страницы / Фронт';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Добавить страницу', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                [
                    'attribute' => 'name',
                    'value' => function(Page $page) {
                        return Html::a($page->name, ['view', 'id' => $page->id]);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'slug',
                    'value' => function(Page $page) {
                        return Html::a(
                            $page->slug,
                            Yii::$app->frontendUrlManager->createAbsoluteUrl(['page/view', 'slug' => $page->slug], []),
                            ['target' => '_blank']
                        );
                    },
                    'format' => 'raw',
                ],
                ['class' => ActionColumn::class],
            ],
        ]); ?>
    </div>
</div>
