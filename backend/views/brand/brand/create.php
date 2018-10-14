<?php

/* @var $this yii\web\View */
/* @var $model \core\forms\Brand\BrandForm */

$this->title = 'Новый бренд';
$this->params['breadcrumbs'][] = ['label' => 'Бренды', 'url' => ['active']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="board-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
