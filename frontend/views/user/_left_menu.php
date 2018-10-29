<?php
use yii\helpers\Url;
use core\helpers\OrderHelper;
use core\helpers\DialogHelper;

/* @var $user \core\entities\User\User */
/* @var $link string */

$controller = Yii::$app->controller->id;
$link = Yii::$app->controller->action->id;

?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">id <?= $user->id ?></h3>
    </div>
    <div class="panel-body">
        <ul class="nav nav-pills nav-stacked">
            <li role="presentation"<?= $link == 'profile' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/user/account/profile']) ?>">Мои данные</a></li>
            <?php if ($user->isCompany()): ?>
                <li role="presentation"<?= $link == 'company' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/user/account/company']) ?>">Моя компания</a></li>
                <li role="presentation"<?= $controller == 'user/trade' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/user/trade/active']) ?>"><?= Yii::$app->settings->get(\core\components\SettingsManager::TRADE_NAME_UP) ?></a></li>
                <li role="presentation"<?= $controller == 'user/cnews' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/user/cnews/active']) ?>"><?= Yii::$app->settings->get(\core\components\SettingsManager::CNEWS_NAME_UP) ?></a></li>
                <li role="presentation"<?= $controller == 'user/expo' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/user/expo/active']) ?>"><?= Yii::$app->settings->get(\core\components\SettingsManager::EXPO_NAME_UP) ?></a></li>
            <?php endif; ?>
            <li role="presentation"><a href="<?= Url::to(['/user/order/orders']) ?>">Заказы<?= OrderHelper::getNewOrdersCount($user) ?></a></li>
            <li role="presentation"><a href="<?= Url::to(['/user/message/dialogs']) ?>">Сообщения<?= DialogHelper::getNewMessagesCount($user) ?></a></li>
            <li role="presentation"<?= $controller == 'user/board' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/user/board/active']) ?>">Объявления</a></li>
            <li role="presentation"><a href="<?= Url::to(['/user/account/profile']) ?>">Служба поддержки</a></li>
        </ul>
    </div>
</div>
