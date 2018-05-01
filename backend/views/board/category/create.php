<?php

/* @var $this yii\web\View */
/* @var $model \core\forms\manage\Board\BoardCategoryForm */

$this->title = 'Новый раздел';
$this->params['breadcrumbs'][] = ['label' => 'Разделы ДО', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<?= $this->render('_form', [
    'model' => $model,
]) ?>