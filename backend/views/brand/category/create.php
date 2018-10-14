<?php

/* @var $this yii\web\View */
/* @var $model \core\forms\manage\Brand\BrandCategoryForm */

$this->title = 'Новый бренд';
$this->params['breadcrumbs'][] = ['label' => 'Разделы Брендов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<?= $this->render('_form', [
    'model' => $model,
]) ?>