<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $page core\entities\Page */

$this->title = Html::encode($page->name);
$this->params['breadcrumbs'][] = ['label' => 'Страницы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-view box box-primary">
    <div class="box-header">
        <?= Html::a('Редактировать', ['update', 'id' => $page->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $page->id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => 'Удалить данную страницу?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
    <div class="box-body table-responsive">
        <?= DetailView::widget([
            'model' => $page,
            'attributes' => [
                'id',
                'name',
                'slug',
                //'content:html',
                'meta_title',
                'meta_description:ntext',
                'meta_keywords',
            ],
        ]) ?>
    </div>
</div>

<div class="page-view box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Контент</h3>
    </div>
    <div class="box-body table-responsive">
        <?= \core\helpers\TextHelper::out($page->content, '', false, false) ?>
    </div>
</div>
