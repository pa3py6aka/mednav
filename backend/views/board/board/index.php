<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\BoardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Объявления';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="board-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Добавить объявление', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],

                //'id',
                'author_id',
                'name',
                'slug',
                'category_id',
                // 'title',
                // 'description:ntext',
                // 'keywords:ntext',
                // 'note',
                // 'price',
                // 'currency',
                // 'price_from',
                // 'full_text:ntext',
                // 'term_id',
                // 'geo_id',
                // 'status',
                // 'active_until',
                // 'created_at',
                // 'updated_at',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
