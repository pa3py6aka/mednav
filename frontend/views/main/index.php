<?php

use core\entities\ContentBlock;
use frontend\widgets\ContentBlock\ShowContentBlock;
use core\components\Settings;

/* @var $this yii\web\View */

$this->title = Yii::$app->settings->get(Settings::GENERAL_TITLE);
$this->registerMetaTag(['name' => 'description', 'content' => Yii::$app->settings->get(Settings::GENERAL_DESCRIPTION)]);
$this->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->settings->get(Settings::GENERAL_KEYWORDS)]);

?>
<div class="row">
    <div class="col-md-3 hidden-sm hidden-xs">
        <div id="leftCol"> <!--left col-->

            <?= ShowContentBlock::widget([
                'module' => ContentBlock::MODULE_MAIN_PAGE,
                'place' => ContentBlock::PLACE_SIDEBAR_LEFT,
                'page' => ContentBlock::PAGE_LISTING,
            ]) ?>

        </div> <!-- // left col-->
    </div>

    <div class="col-md-6 col-sm-9 col-xs-12">

        <?= ShowContentBlock::widget([
            'module' => ContentBlock::MODULE_MAIN_PAGE,
            'place' => ContentBlock::PLACE_MAIN,
            'page' => ContentBlock::PAGE_LISTING,
        ]) ?>

    </div>

    <!-- right col -->
    <div class="col-md-3 col-sm-3 hidden-xs">
        <div id="rightCol">
            <div style="margin: 10px 0;"><img src="img/234.png" class="img-responsive" alt=""></div>

            <?= ShowContentBlock::widget([
                'module' => ContentBlock::MODULE_MAIN_PAGE,
                'place' => ContentBlock::PLACE_SIDEBAR_RIGHT,
                'page' => ContentBlock::PAGE_LISTING,
            ]) ?>

        </div><!-- // right col -->
    </div>
</div>
