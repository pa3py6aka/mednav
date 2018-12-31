<?php
use yii\helpers\Url;
use yii\helpers\Html;
use core\helpers\OrderHelper;
use core\helpers\DialogHelper;
use core\components\Settings;

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
            <li role="presentation"<?= $controller == 'user/message' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/user/message/dialogs']) ?>">Сообщения<?= DialogHelper::getNewMessagesCount($user) ?></a></li>

            <?php if ($user->isCompany()): ?>
                <li role="presentation"<?= $link == 'company' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/user/account/company']) ?>"><?= Yii::$app->settings->get(Settings::COMPANY_NAME_UP) ?></a></li>
                <li role="presentation"<?= $controller == 'user/trade' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/user/trade/active']) ?>"><?= Yii::$app->settings->get(Settings::TRADE_NAME_UP) ?></a></li>
            <?php endif; ?>

            <li role="presentation"<?= $controller == 'user/board' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/user/board/active']) ?>"><?= Yii::$app->settings->get(Settings::BOARD_NAME_UP) ?></a></li>

            <?php if ($user->isCompany()): ?>
                <li role="presentation"<?= $controller == 'user/cnews' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/user/cnews/active']) ?>"><?= Yii::$app->settings->get(Settings::CNEWS_NAME_UP) ?></a></li>
                <?php /*<li role="presentation"<?= $controller == 'user/expo' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/user/expo/active']) ?>"><?= Yii::$app->settings->get(Settings::EXPO_NAME_UP) ?></a></li> */ ?>
            <?php endif; ?>

            <li role="presentation"<?= $controller == 'user/order' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/user/order/orders']) ?>">Заказы<?= OrderHelper::getNewOrdersCount($user) ?></a></li>
            <li role="presentation"<?= $controller == 'user/support' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/user/support/dialogs']) ?>">Служба поддержки<?= DialogHelper::getSupportNewMessagesCount($user) ?></a></li>

            <?php foreach ((new \core\readModels\PageReadRepository())->getUCPPagesLinks() as $slug => $name): ?>
                <li role="presentation"<?= $controller === 'page' && Yii::$app->request->get('slug') === $slug ? ' class="active"' : '' ?>><a href="<?= Url::to(['/page/view', 'slug' => $slug]) ?>"><?= Html::encode($name) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
