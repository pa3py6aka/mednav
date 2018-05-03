<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model core\entities\Board\Board */

$this->title = 'Update Board: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Boards', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="board-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
