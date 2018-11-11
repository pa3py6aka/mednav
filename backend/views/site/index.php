<?php

use core\components\Settings;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Панель управления сайтом';
?>
<div class="row">
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Пользователи</span>
                <span class="info-box-number">
                    На модерации: <a href="<?= Url::to(['/user/index', 'UserSearch[status]' => \core\entities\User\User::STATUS_ON_PREMODERATION]) ?>"><?= \core\entities\User\User::find()->onModeration()->count() ?></a>
                </span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-newspaper-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text"><?= Yii::$app->settings->get(Settings::BOARD_NAME) ?></span>
                <span class="info-box-number">
                    На модерации: <a href="<?= Url::to(['/board/board/moderation']) ?>"><?= \core\entities\Board\Board::find()->onModeration()->count() ?></a>
                </span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-cubes"></i></span>
            <div class="info-box-content">
                <span class="info-box-text"><?= Yii::$app->settings->get(Settings::TRADE_NAME) ?></span>
                <span class="info-box-number">
                    На модерации: <a href="<?= Url::to(['/trade/trade/moderation']) ?>"><?= \core\entities\Trade\Trade::find()->onModeration()->count() ?></a>
                </span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-book"></i></span>
            <div class="info-box-content">
                <span class="info-box-text"><?= Yii::$app->settings->get(Settings::COMPANY_NAME) ?></span>
                <span class="info-box-number">
                    На модерации: <a href="<?= Url::to(['/company/company/moderation']) ?>"><?= \core\entities\Company\Company::find()->onModeration()->count() ?></a>
                </span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-tasks"></i></span>
            <div class="info-box-content">
                <span class="info-box-text"><?= Yii::$app->settings->get(Settings::CNEWS_NAME) ?></span>
                <span class="info-box-number">
                    На модерации: <a href="<?= Url::to(['/cnews/cnews/moderation']) ?>"><?= \core\entities\CNews\CNews::find()->onModeration()->count() ?></a>
                </span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-calendar"></i></span>
            <div class="info-box-content">
                <span class="info-box-text"><?= Yii::$app->settings->get(Settings::EXPO_NAME) ?></span>
                <span class="info-box-number">
                    На модерации: <a href="<?= Url::to(['/expo/expo/moderation']) ?>"><?= \core\entities\Expo\Expo::find()->onModeration()->count() ?></a>
                </span>
            </div><!-- /.info-box-content -->
        </div><!-- /.info-box -->
    </div>
</div>

