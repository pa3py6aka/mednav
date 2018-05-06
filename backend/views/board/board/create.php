<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model \core\forms\manage\Board\BoardManageForm */

$this->title = 'Новое объявление';
$this->params['breadcrumbs'][] = ['label' => 'Объявления', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="board-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
