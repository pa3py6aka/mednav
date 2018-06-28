<?php

/* @var $this yii\web\View */
/* @var $model \core\forms\manage\Trade\TradeCategoryForm */

$this->title = 'Новый раздел';
$this->params['breadcrumbs'][] = ['label' => 'Разделы КТ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<?= $this->render('_form', [
    'model' => $model,
]) ?>