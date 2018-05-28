<?php

/** @var array $params */

return [
    'class' => 'yii\web\UrlManager',
    'hostInfo' => $params['frontendHostInfo'],
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'normalizer' => [
        'class' => \yii\web\UrlNormalizer::class,
        'action' => \yii\web\UrlNormalizer::ACTION_REDIRECT_PERMANENT,
    ],
    'rules' => [
        '' => 'main/index',
        'signup' => 'auth/signup/request',
        'signup/<_a:[\w-]+>' => 'auth/signup/<_a>',
        '<_a:login|logout>' => 'auth/auth/<_a>',

        'account' => 'user/account/index',
        'account/<_a:[\w-]+>' => 'user/account/<_a>',

        'board/<id:\d+>-<slug:[\w-]+>' => 'board/board/view',

        'board/<region:[\w-]+>/<category:[\w-]+>/page<page:\d+>' => 'board/board/index',
        'board/<region:[\w-]+>/page<page:\d+>' => 'board/board/index',
        'board/page<page:\d+>' => 'board/board/index',

        'board/<region:[\w-]+>/<category:[\w-]+>' => 'board/board/index',
        'board/<region:[\w-]+>' => 'board/board/index',
        'board' => 'board/board/index',

        '<_c:[\w\-]+>' => '<_c>/index',
        '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
        '<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_c>/<_a>',
        '<_c:[\w\-]+>/<_a:[\w-]+>' => '<_c>/<_a>',
    ],
];