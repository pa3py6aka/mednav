<?php

namespace core\helpers;


use Yii;
use yii\helpers\Url;

class UrlHelper
{
    public static function getUrl($url, $ignoreParam): string
    {
        $params = Yii::$app->getRequest()->getQueryParams();
        if (isset($params[$ignoreParam])) {
            unset($params[$ignoreParam]);
        }
        return Url::to(array_merge(((array) $url), $params));
    }
}