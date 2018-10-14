<?php

/* @var $this yii\web\View */
/* @var $model \core\forms\Brand\BrandForm */

$this->title = 'Редактирование бренда #' . $model->article->id;
$this->params['breadcrumbs'][] = ['label' => 'Бренды', 'url' => ['active']];
$this->params['breadcrumbs'][] = ['label' => '#' . $model->article->id, 'url' => ['view', 'id' => $model->article->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="board-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
