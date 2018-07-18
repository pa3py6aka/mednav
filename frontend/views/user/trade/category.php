<?php

use core\entities\Trade\TradeUserCategory;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $category TradeUserCategory */

$this->title = 'Личный кабинет | ' . Html::encode($category->name);

?>
<div class="row">
    <div class="col-md-3">
        <?= $this->render('@frontend/views/user/_left_menu', ['user' => $this->params['user']]) ?>
    </div>
    <div class="col-md-9">
        <?= \frontend\widgets\AccountBreadcrumbs::show([
            ['label' => 'Товары', 'url' => ['/user/trade/active']],
            ['label' => 'Категории', 'url' => ['/user/trade/categories']],
            'Просмотр'
        ]) ?>

        <h1><?= Html::encode($category->name) ?></h1>
        <a href="<?= Url::to(['category-update', 'id' => $category->id]) ?>" class="btn btn-success">Редактировать</a>
        <a href="<?= Url::to(['category-remove', 'id' => $category->id]) ?>" class="btn btn-danger" data-method="post" data-confirm="Удалить данную категорию со всеми привязанными к ней товарами?">Удалить</a>
        <br><br>
        <?= \yii\widgets\DetailView::widget([
            'model' => $category,
            'attributes' => [
                'id',
                'name:ntext',
                [
                    'label' => 'Кол-во товаров',
                    'value' => function (TradeUserCategory $category) {
                        return $category->getTrades()->count();
                    },
                ],
                [
                    'attribute' => 'category_id',
                    'value' => function (TradeUserCategory $category) {
                        return $category->category->name;
                    },
                ],
                [
                    'attribute' => 'uom_id',
                    'value' => function (TradeUserCategory $category) {
                        return $category->uom->name;
                    },
                ],
                [
                    'attribute' => 'currency_id',
                    'value' => function (TradeUserCategory $category) {
                        return $category->currency->name;
                    },
                ],
                'wholesale:boolean',
            ],
        ]) ?>
    </div>
</div>

