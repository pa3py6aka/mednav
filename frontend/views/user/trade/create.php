<?php

use core\components\ImageManager\ImageManagerAsset;
use core\forms\manage\Trade\TradeManageForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model TradeManageForm */

$this->title = 'Личный кабинет | Добавление товара';
$this->registerJsVar('_ImageUploadAction', Url::to(['/user/trade/upload']));
ImageManagerAsset::register($this);

?>
<div class="row">
    <div class="col-md-3">
        <?= $this->render('@frontend/views/user/_left_menu', ['user' => $this->params['user']]) ?>
    </div>

    <div class="col-md-9">
        <?= \frontend\widgets\AccountBreadcrumbs::show([['label' => Yii::$app->settings->get(\core\components\SettingsManager::TRADE_NAME_UP), 'url' => ['/user/trade/active']], 'Новый товар']) ?>
        <h1>Новый товар</h1>

        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</div>