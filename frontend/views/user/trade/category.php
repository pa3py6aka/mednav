<?php

use core\entities\Trade\TradeUserCategory;
use core\helpers\PaginationHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use core\helpers\HtmlHelper;
use core\entities\Trade\Trade;

/* @var $this yii\web\View */
/* @var $category TradeUserCategory */
/* @var $tradesProvider \yii\data\ActiveDataProvider */

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
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <?php /*<!-- Данные по категории -->
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="category-details-header">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#category-details-view" aria-expanded="false" aria-controls="category-details-view">
                            Данные по категории
                        </a>
                    </h4>
                </div>
                <div id="category-details-view" class="panel-collapse collapse" role="tabpanel" aria-labelledby="category-details-header">
                    <div class="panel-body">
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
            </div> */ ?>
            <!-- Товары категории -->
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="category-trades-header">
                    <h4 class="panel-title">
                        <!--<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#category-trades-view" aria-expanded="true" aria-controls="category-trades-view">-->
                            Товары
                        <!--</a>-->
                    </h4>
                </div>
                <div id="category-trades-view" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="category-trades-header">
                    <div class="panel-body">
                        <span class="pull-right">
                            <?= PaginationHelper::pageSizeSelector($tradesProvider->pagination, PaginationHelper::SITE_SIZES) ?>
                        </span>
                        <a href="<?= Url::to(['create', 'category' => $category->id]) ?>" class="btn btn-success">Добавить товар</a>
                        <?= HtmlHelper::actionButtonForSelected('Удалить выбранные', 'remove', 'danger') ?><br><br>
                        <?= \yii\grid\GridView::widget([
                            'dataProvider' => $tradesProvider,
                            'layout' => "{items}\n{summary}\n{pager}",
                            'id' => 'grid',
                            'columns' => [
                                ['class' => \yii\grid\CheckboxColumn::class],
                                ['class' => \yii\grid\SerialColumn::class],
                                [
                                    'attribute' => 'name',
                                    'value' => function (Trade $trade) {
                                        return Html::a(Html::encode($trade->name), $trade->getUrl());
                                    },
                                    'format' => 'raw',
                                ],
                                [
                                    'attribute' => 'stock',
                                    'value' => function (Trade $trade) {
                                        return $trade->getStockString();
                                    },
                                ],
                                [
                                    'class' => \core\grid\EditColumn::class,
                                    'attribute' => 'price',
                                    'value' => function (Trade $trade) {
                                        return $trade->getPriceString();
                                    },
                                    'format' => 'raw',
                                ],
                                [
                                    'attribute' => 'category_id',
                                    'value' => function (Trade $trade) {
                                        return Html::encode($trade->category->name);
                                    },
                                    'format' => 'raw',
                                ],
                                ['class' => \yii\grid\ActionColumn::class, 'template' => '{update} / {delete}', 'buttons' => [
                                    'update' => function ($url, Trade $trade, $key) {
                                        return Html::a('Редактировать', $url);
                                    },
                                    'delete' => function ($url, Trade $trade, $key) {
                                        return Html::a('Удалить', $url, ['data-method' => 'post', 'data-confirm' => 'Удалить данный товар?']);
                                    }
                                ]],
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

