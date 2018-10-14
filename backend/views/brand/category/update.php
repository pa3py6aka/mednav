<?php

/* @var $this yii\web\View */
/* @var $category \core\entities\Brand\BrandCategory */
/* @var $model \core\forms\manage\Brand\BrandCategoryForm */
/* @var $tab string */

$this->title = 'Раздел Брендов #' . $category->id;
$this->params['breadcrumbs'][] = ['label' => 'Разделы Брендов', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактирование';

?>
<div class="board-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>