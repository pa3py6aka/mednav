<?php

/* @var $this yii\web\View */
/* @var $category \core\entities\CNews\CNewsCategory */
/* @var $model \core\forms\manage\CNews\CNewsCategoryForm */
/* @var $tab string */

$this->title = 'Раздел Новостей компаний #' . $category->id;
$this->params['breadcrumbs'][] = ['label' => 'Разделы Новостей компаний', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактирование';

?>
<div class="board-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>