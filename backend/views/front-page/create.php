<?php

/* @var $this yii\web\View */
/* @var $model \core\forms\PageForm */

$this->title = 'Новая страница';
$this->params['breadcrumbs'][] = ['label' => 'Страницы / Фронт', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
