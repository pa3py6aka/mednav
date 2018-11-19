<?php

namespace core\helpers;


use Yii;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

class TextHelper
{
    public static function out($content, $module, $noFollow = false, $indirectLink = true): string
    {
        Yii::$app->view->registerJs("$('.pury-content').find('a').attr('target', '_blank')" . ($noFollow ? ".attr('rel', 'nofollow')" : "") . ";",  Yii::$app->view::POS_READY, 'addTargetBlank');

        $config = [];
        if ($indirectLink) {
            $url = $module === 'company'
                ? Url::to(['/company/outsite']) . '?url=%s'
                : Url::to(['/site/outsite', 'module' => $module]) . '?id=%s';
            $config['URI.Munge'] = $url;
        }

        $out = HtmlPurifier::process($content, $config);

        return '<div class="pury-content">' . $out . '</div>';
    }
}