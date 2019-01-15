<?php

/* @var $this yii\web\View */
/* @var $model \core\forms\manage\Trade\TradeUserCategoryForm */
/* @var $userCategory \core\entities\Trade\TradeUserCategory */

$this->title = \yii\helpers\Html::encode($userCategory->name);
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['/trade/trade/active']];
$this->params['breadcrumbs'][] = ['label' => 'Пользовательские категории', 'url' => ['/trade/user-category/index']];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="trade-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
