<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \core\forms\Article\ArticleForm */

$this->title = 'Редактирование статьи #' . $model->article->id;
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['active']];
$this->params['breadcrumbs'][] = ['label' => '#' . $model->article->id, 'url' => ['view', 'id' => $model->article->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="board-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
