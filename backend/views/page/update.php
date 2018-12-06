<?php

/* @var $this yii\web\View */
/* @var $page core\entities\Page */
/* @var $model \core\forms\PageForm */

$this->title = 'Редактирование страницы #' . $page->id;
$this->params['breadcrumbs'][] = ['label' => 'Страницы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $page->name, 'url' => ['view', 'id' => $page->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="page-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
