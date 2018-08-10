<?php

/* @var $this yii\web\View */
/* @var $category \core\entities\Article\ArticleCategory */
/* @var $model \core\forms\manage\Article\ArticleCategoryForm */
/* @var $tab string */

$this->title = 'Раздел Статей #' . $category->id;
$this->params['breadcrumbs'][] = ['label' => 'Разделы Статей', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактирование';



?>
<div class="board-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>