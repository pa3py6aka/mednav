<?php

/* @var $this yii\web\View */
/* @var $category \core\entities\Expo\ExpoCategory */
/* @var $model \core\forms\manage\Expo\ExpoCategoryForm */
/* @var $tab string */

$this->title = 'Раздел Выставок #' . $category->id;
$this->params['breadcrumbs'][] = ['label' => 'Разделы Выставок', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактирование';

?>
<div class="board-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>