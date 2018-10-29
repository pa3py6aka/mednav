<?php

use core\forms\manage\Trade\TradeUserCategoryForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model TradeUserCategoryForm */


$this->title = 'Личный кабинет | Редактирование пользовательской категории';

?>
<div class="row">
    <div class="col-md-3">
        <?= $this->render('@frontend/views/user/_left_menu', ['user' => $this->params['user']]) ?>
    </div>

    <div class="col-md-9">
        <?= \frontend\widgets\AccountBreadcrumbs::show([
            ['label' => Yii::$app->settings->get(\core\components\SettingsManager::TRADE_NAME_UP), 'url' => ['/user/trade/active']],
            'Редактирование категории'
        ]) ?>
        <h1>Редактирование категории</h1>

        <?= $this->render('_category-form', ['model' => $model]) ?>
    </div>
</div>