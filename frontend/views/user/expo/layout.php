<?php
use core\components\Settings;
use core\helpers\PaginationHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use core\helpers\HtmlHelper;

/* @var $this yii\web\View */
/* @var $tab string */
/* @var $pagination \yii\data\Pagination|null */

/* @var $content string */

$this->title = 'Личный кабинет | ' . Yii::$app->settings->get(Settings::EXPO_NAME_UP);
$tab =  ArrayHelper::getValue($this->params, 'tab', 'active');
$pagination = ArrayHelper::getValue($this->params, 'pagination');

$this->beginContent('@frontend/views/layouts/main.php');
?>
<div class="row">
    <div class="col-md-3">
        <?= $this->render('@frontend/views/user/_left_menu', ['user' => $this->params['user']]) ?>
    </div>

    <div class="col-md-9">
        <?= \frontend\widgets\AccountBreadcrumbs::show([Yii::$app->settings->get(Settings::EXPO_NAME_UP)]) ?>
        <h1><?= Yii::$app->settings->get(Settings::EXPO_NAME_UP) ?></h1>

        <p>
            <a href="<?= Url::to(['create']) ?>" class="btn btn-success">Добавить выставку</a>
            <?php if ($tab == 'active' || $tab == 'waiting') {
                echo HtmlHelper::actionButtonForSelected('Удалить выбранные', 'remove', 'danger');
            } ?>
        </p>

        <ul class="nav nav-tabs">
            <li<?= $tab == 'active' ? ' class="active"' : '' ?>><a href="<?= Url::to(['active']) ?>">Размещённые</a></li>
            <li<?= $tab == 'waiting' ? ' class="active"' : '' ?>><a href="<?= Url::to(['waiting']) ?>">На проверке (<?= \core\entities\Expo\Expo::find()->onModerationCount($this->params['user']['id']) ?>)</a></li>
            <span class="pull-right">
                <?php if ($pagination) {
                    echo PaginationHelper::pageSizeSelector($pagination, PaginationHelper::SITE_SIZES);
                } ?>
            </span>
        </ul>
        <br>
        <div class="tab-content">
            <div class="tab-pane fade active in">
                <?= $content ?>
            </div>
        </div>
    </div>
</div>
<?php $this->endContent() ?>