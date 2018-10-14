<?php
use yii\helpers\Url;
use core\entities\CNews\CNews;

/* @var $tab string */

?>
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation"<?= $tab == 'active' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/cnews/cnews/active']) ?>">Размещённые</a></li>
    <li role="presentation"<?= $tab == 'moderation' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/cnews/cnews/moderation']) ?>">На проверку (<?= CNews::find()->onModeration()->count() ?>)</a></li>
    <li role="presentation"<?= $tab == 'deleted' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/cnews/cnews/deleted']) ?>">Удалённые</a></li>
</ul>
