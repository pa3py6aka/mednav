<?php

/* @var $this yii\web\View */
/* @var $category \core\entities\News\NewsCategory */
/* @var $model \core\forms\manage\News\NewsCategoryForm */
/* @var $tab string */

$this->title = 'Раздел Новостей #' . $category->id;
$this->params['breadcrumbs'][] = ['label' => 'Разделы Новостей', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактирование';

?>
<div class="board-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>