<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $company \core\entities\Company\Company */

?>
<h3>Контакты</h3>
<b>Адрес:</b> <?= Html::encode($company->address) ?><br />
<b>Телефон:</b> <?= $company->getPhones(true) ?><br />
<b>Факс:</b> <?= Html::encode($company->fax) ?><br />
<b>Сайт:</b> <a href="<?= Url::to(['/company/company/outsite', 'id' => $company->id]) ?>" rel="nofollow" target="_blank"><?= Html::encode($company->site) ?></a><br />
<b>Почта:</b> <a href="mailto:<?= $company->email ?>"><?= $company->email ?></a>
