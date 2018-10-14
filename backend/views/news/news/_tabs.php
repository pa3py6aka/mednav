<?php
use yii\helpers\Url;
use core\entities\News\News;

/* @var $tab string */

?>
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation"<?= $tab == 'active' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/news/news/active']) ?>">Размещённые</a></li>
    <li role="presentation"<?= $tab == 'moderation' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/news/news/moderation']) ?>">На проверку (<?= News::find()->onModeration()->count() ?>)</a></li>
    <li role="presentation"<?= $tab == 'deleted' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/news/news/deleted']) ?>">Удалённые</a></li>
</ul>
