<?php

/* @var $this yii\web\View */
/* @var $model \core\forms\manage\Expo\ExpoCategoryForm */

$this->title = 'Новая выставка';
$this->params['breadcrumbs'][] = ['label' => 'Разделы Выставок', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<?= $this->render('_form', [
    'model' => $model,
]) ?>