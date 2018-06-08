<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model \core\forms\Company\CompanyForm */

$this->title = 'Новая компания';
$this->params['breadcrumbs'][] = ['label' => 'Компании', 'url' => ['active']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="board-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
