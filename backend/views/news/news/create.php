<?php

/* @var $this yii\web\View */
/* @var $model \core\forms\News\NewsForm */

$this->title = 'Новая новость';
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['active']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="board-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
