<?php

use core\helpers\PaginationHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use core\helpers\BoardHelper;
use core\helpers\HtmlHelper;

/* @var $this yii\web\View */
/* @var $tab string */
/* @var $pagination \yii\data\Pagination|null */

$this->title = 'Личный кабинет | Мои объявления';
$tab =  ArrayHelper::getValue($this->params, 'tab', 'active');
$pagination = ArrayHelper::getValue($this->params, 'pagination');

$this->beginContent('@frontend/views/layouts/main.php');
?>
<div class="row">
    <div class="col-md-3">
        <?= $this->render('@frontend/views/user/_left_menu', ['user' => $this->params['user']]) ?>
    </div>

    <div class="col-md-9">
        <?= \frontend\widgets\AccountBreadcrumbs::show(['Мои объявления']) ?>
        <h1>Мои объявления</h1>

        <p>
            <a href="<?= Url::to(['create']) ?>" class="btn btn-primary">Добавить объявление</a>
            <?php if ($tab == 'active') {
                echo HtmlHelper::actionButtonForSelected('Удалить выбранные', 'remove', 'danger');
            } else if ($tab == 'archive') {
                echo HtmlHelper::actionButtonForSelected('Продлить выбранные', 'extend', 'success');
            } ?>
        </p>

        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"<?= $tab == 'active' ? ' class="active"' : '' ?>><a href="<?= Url::to(['active']) ?>">Размещённые</a></li>
            <li role="presentation"<?= $tab == 'archive' ? ' class="active"' : '' ?>><a href="<?= Url::to(['archive']) ?>">Архив</a></li>
            <li role="presentation"<?= $tab == 'waiting' ? ' class="active"' : '' ?>><a href="<?= Url::to(['waiting']) ?>">На проверке (<?= BoardHelper::getWaitingCountFor() ?>)</a></li>
            <span class="pull-right">
                <?php if ($pagination) {
                    echo PaginationHelper::pageSizeSelector($pagination, PaginationHelper::SITE_SIZES);
                } ?>
            </span>
        </ul>
        <br>
        <div class="tab-content">
            <div class="tab-pane fade active in" role="tabpanel">
                <?= $content ?>
            </div>
        </div>
    </div>
</div>
<?php $this->endContent() ?>