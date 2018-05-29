<?php

namespace core\helpers;


use Yii;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

class TextHelper
{
    public static function out($content): string
    {
        Yii::$app->view->registerJs("$('.pury-content').find('a').attr('target', '_blank');",  Yii::$app->view::POS_READY, 'addTargetBlank');
        $out = HtmlPurifier::process($content, [
            'URI.Munge' => Url::to(['/site/outsite']) . '?url=%s'
        ]);

        return '<div class="pury-content">' . $out . '</div>';
    }
}