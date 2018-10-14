<?php

/* @var $this yii\web\View */
/* @var $model \core\forms\CNews\CNewsForm */

$this->title = 'Новая новость';
$this->params['breadcrumbs'][] = ['label' => 'Новости компаний', 'url' => ['active']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="board-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
