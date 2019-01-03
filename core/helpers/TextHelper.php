<?php

namespace core\helpers;


use Yii;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use yii\web\Cookie;

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

    public static function welcomeUserBlock(): string
    {
        $cookies = Yii::$app->request->cookies;
        if (!$cookies->get('user_welcome')) {
            Yii::$app->response->cookies->add(new Cookie([
                'name' => 'user_welcome',
                'value' => 1
            ]));

            $html = <<<HTML
<div id="info" class="alert-info alert fade in">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        Добро пожаловать на сайт http://mednav.ru
</div>
HTML;
            return $html;
        }

        return '';
    }
}