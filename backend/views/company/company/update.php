<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \core\forms\Company\CompanyForm */

$this->title = 'Редактирование компании #' . $model->company->id;
$this->params['breadcrumbs'][] = ['label' => 'Компании', 'url' => ['active']];
$this->params['breadcrumbs'][] = ['label' => '#' . $model->company->id, 'url' => ['view', 'id' => $model->company->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="board-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
