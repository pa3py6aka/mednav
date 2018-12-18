<?php
use yii\helpers\Html;
use yii\helpers\Url;
use core\helpers\HtmlHelper;

/* @var $this \yii\web\View */
/* @var $company \core\entities\Company\Company */

$this->title = $company->getFullName() . ($company->geo ? ' ' . $company->geo->name : '') . ', контакты компании';
$this->registerMetaTag(['name' => 'description', 'content' => $company->getFullName() . ($company->geo ? ' ' . $company->geo->name : '') . ', ' . Html::encode($company->address) . ', ' . $company->getPhones(true)], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => $company->getFullName() . ($company->geo ? ', ' . $company->geo->name : '')], 'keywords');

?>
<h3>Контакты</h3>
<?= HtmlHelper::showIfIs('<b>Адрес:</b> ' . Html::encode($company->address) . '<br>', $company->address) ?>
<?= HtmlHelper::showIfIs('<b>Телефон:</b> ' . $company->getPhones(true) . '<br>', $company->getPhones()) ?>
<?= HtmlHelper::showIfIs('<b>Факс:</b> ' . Html::encode($company->fax) . '<br>', $company->fax) ?>
<?= HtmlHelper::showIfIs('<b>Сайт:</b> <a href="' . Url::to(['/company/company/outsite', 'id' => $company->id]) . '" rel="nofollow" target="_blank">' . Html::encode($company->site) . '</a><br>', $company->site) ?>
<?= HtmlHelper::showIfIs('<b>Почта:</b> <a href="mailto:' . $company->email . '">' . $company->email . '</a><br>', $company->email) ?>
