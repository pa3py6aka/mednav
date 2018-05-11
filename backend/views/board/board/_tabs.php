<?php
use yii\helpers\Url;

/* @var $tab string */

?>
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation"<?= $tab == 'active' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/board/board/active']) ?>">Размещённые</a></li>
    <li role="presentation"<?= $tab == 'archive' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/board/board/archive']) ?>">Архив</a></li>
    <li role="presentation"<?= $tab == 'moderation' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/board/board/moderation']) ?>">На проверку</a></li>
    <li role="presentation"<?= $tab == 'deleted' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/board/board/deleted']) ?>">Удалённые</a></li>
</ul>
