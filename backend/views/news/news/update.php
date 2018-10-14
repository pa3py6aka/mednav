<?php

/* @var $this yii\web\View */
/* @var $model \core\forms\News\NewsForm */

$this->title = 'Редактирование новости #' . $model->article->id;
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['active']];
$this->params['breadcrumbs'][] = ['label' => '#' . $model->article->id, 'url' => ['view', 'id' => $model->article->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="board-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
