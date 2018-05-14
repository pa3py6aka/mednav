<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \core\forms\manage\Board\BoardManageForm */
/* @var $board core\entities\Board\Board */

$this->title = 'Редактирование объявления №' . $board->id;
$this->params['breadcrumbs'][] = ['label' => 'Доска объявлений', 'url' => ['active']];
$this->params['breadcrumbs'][] = ['label' => '#' . $board->id, 'url' => ['view', 'id' => $board->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="board-update">

    <?= $this->render('_form', [
        'model' => $model,
        'board' => $board,
    ]) ?>

</div>
