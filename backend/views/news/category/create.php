<?php

/* @var $this yii\web\View */
/* @var $model \core\forms\manage\News\NewsCategoryForm */

$this->title = 'Новый раздел';
$this->params['breadcrumbs'][] = ['label' => 'Разделы Новостей', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<?= $this->render('_form', [
    'model' => $model,
]) ?>