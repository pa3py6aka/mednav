<?php

/* @var $this yii\web\View */
/* @var $model \core\forms\Expo\ExpoForm */

$this->title = 'Новая выставка';
$this->params['breadcrumbs'][] = ['label' => 'Выставки', 'url' => ['active']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="board-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
