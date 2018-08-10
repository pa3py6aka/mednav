<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model \core\forms\Article\ArticleForm */

$this->title = 'Новая статья';
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['active']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="board-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
