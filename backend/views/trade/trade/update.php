<?php

/* @var $this yii\web\View */
/* @var $model \core\forms\manage\Trade\TradeManageForm */
/* @var $trade core\entities\Trade\Trade */

$this->title = 'Редактирование товара №' . $trade->id;
$this->params['breadcrumbs'][] = ['label' => 'Каталог товаров', 'url' => ['active']];
$this->params['breadcrumbs'][] = ['label' => '#' . $trade->id, 'url' => ['view', 'id' => $trade->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="trade-update">

    <?= $this->render('_form', [
        'model' => $model,
        'trade' => $trade,
    ]) ?>

</div>
