<?php

/* @var $this yii\web\View */
/* @var $model \core\forms\manage\Company\CompanyCategoryForm */

$this->title = 'Новый раздел';
$this->params['breadcrumbs'][] = ['label' => 'Разделы КК', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<?= $this->render('_form', [
    'model' => $model,
]) ?>