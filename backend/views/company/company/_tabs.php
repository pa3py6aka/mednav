<?php
use yii\helpers\Url;
use core\entities\Company\Company;

/* @var $tab string */

?>
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation"<?= $tab == 'active' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/company/company/active']) ?>">Размещённые</a></li>
    <li role="presentation"<?= $tab == 'moderation' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/company/company/moderation']) ?>">На проверку (<?= Company::find()->onModeration()->count() ?>)</a></li>
    <li role="presentation"<?= $tab == 'deleted' ? ' class="active"' : '' ?>><a href="<?= Url::to(['/company/company/deleted']) ?>">Удалённые</a></li>
</ul>
