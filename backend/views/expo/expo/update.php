<?php

/* @var $this yii\web\View */
/* @var $model \core\forms\Expo\ExpoForm */

$this->title = 'Редактирование выставки #' . $model->article->id;
$this->params['breadcrumbs'][] = ['label' => 'Выставки', 'url' => ['active']];
$this->params['breadcrumbs'][] = ['label' => '#' . $model->article->id, 'url' => ['view', 'id' => $model->article->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="board-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
