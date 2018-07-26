<?php

namespace core\helpers;


use Yii;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

class TextHelper
{
    public static function out($content, $noFollow = false): string
    {
        Yii::$app->view->registerJs("$('.pury-content').find('a').attr('target', '_blank')" . ($noFollow ? ".attr('rel', 'nofollow')" : "") . ";",  Yii::$app->view::POS_READY, 'addTargetBlank');
        $out = HtmlPurifier::process($content, [
            'URI.Munge' => Url::to(['/site/outsite']) . '?url=%s'
        ]);

        return '<div class="pury-content">' . $out . '</div>';
    }
}