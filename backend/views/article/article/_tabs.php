<?php
use yii\helpers\Url;
use core\entities\Article\Article;

/* @var $tab string */

?>
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation"<?= $tab == 'active' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/article/article/active']) ?>">Размещённые</a></li>
    <li role="presentation"<?= $tab == 'moderation' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/article/article/moderation']) ?>">На проверку (<?= Article::find()->onModeration()->count() ?>)</a></li>
    <li role="presentation"<?= $tab == 'deleted' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/article/article/deleted']) ?>">Удалённые</a></li>
</ul>
