<?php

/* @var $this yii\web\View */
/* @var $model \core\forms\manage\Trade\TradeManageForm */

$this->title = 'Новый товар';
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['active']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trade-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
