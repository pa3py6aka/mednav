<?php

/* @var $this yii\web\View */
/* @var $model \core\forms\manage\CNews\CNewsCategoryForm */

$this->title = 'Новый раздел';
$this->params['breadcrumbs'][] = ['label' => 'Разделы Новостей компаний', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<?= $this->render('_form', [
    'model' => $model,
]) ?>