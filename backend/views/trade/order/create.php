<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model core\entities\Order\Order */

$this->title = 'Create Order';
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
