<?php

/* @var $this yii\web\View */
/* @var $model \core\forms\manage\Trade\TradeUserCategoryForm */

$this->title = 'Новая пользовательская категория';
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['/trade/trade/active']];
$this->params['breadcrumbs'][] = ['label' => 'Пользовательские категории', 'url' => ['/trade/user-category/index']];
$this->params['breadcrumbs'][] = 'Добавление';
?>
<div class="trade-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
