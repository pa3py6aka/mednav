<?php

use yii\helpers\Html;
use core\helpers\TextHelper;

/* @var $this \yii\web\View */
/* @var $page \core\entities\Page */

$this->title = $page->meta_title ?: $page->name;
$this->registerMetaTag(['name' => 'description', 'content' => $page->meta_description]);
$this->registerMetaTag(['name' => 'keywords', 'content' => $page->meta_keywords]);

?>
<div class="row">
    <div class="col-md-9 col-sm-9 col-xs-12">

            <h1><?= Html::encode($page->name) ?></h1>
            <?= TextHelper::out($page->content, 'pages') ?>

    </div>

    <!-- right col -->
    <div class="col-md-3 col-sm-3 hidden-xs">
        <div id="rightCol">

        </div><!-- // right col -->
    </div>
</div>

