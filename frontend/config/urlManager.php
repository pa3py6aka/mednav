<?php

/** @var array $params */

return [
    'class' => 'yii\web\UrlManager',
    'hostInfo' => $params['frontendHostInfo'],
    'baseUrl' => '/',
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
        '<module:(board|company|brand|cnews|news|articles|expo|site)>/outsite' => 'site/outsite',

        'account' => 'user/account/index',
        'account/<_a:[\w-]+>' => 'user/account/<_a>',

        'contact' => 'site/contact',

        // Объявления
        'board/<id:\d+>-<slug:[\w-]+>' => 'board/board/view',

        'board/<region:[\w-]+>/<category:[\w-]+>/page<page:\d+>' => 'board/board/list',
        'board/<region:[\w-]+>/page<page:\d+>' => 'board/board/list',
        'board/page<page:\d+>' => 'board/board/list',

        'board/<region:[\w-]+>/<category:[\w-]+>' => 'board/board/list',
        'board/<region:[\w-]+>' => 'board/board/list',
        'board' => 'board/board/list',

        // Компании
        'company/<id:\d+>-<slug:[\w-]+>/<_a:contacts|boards|trades|articles|cnews>' => 'company/company/<_a>',
        'company/<id:\d+>-<slug:[\w-]+>' => 'company/company/view',

        'company/<region:[\w-]+>/<category:[\w-]+>/page<page:\d+>' => 'company/company/list',
        'company/<region:[\w-]+>/page<page:\d+>' => 'company/company/list',
        'company/page<page:\d+>' => 'company/company/list',

        'company/<region:[\w-]+>/<category:[\w-]+>' => 'company/company/list',
        'company/<region:[\w-]+>' => 'company/company/list',
        'company' => 'company/company/list',

        // Товары
        'trade/<id:\d+>-<slug:[\w-]+>' => 'trade/trade/view',
        'trade/<_a:(outsite|vendor)>' => 'trade/trade/<_a>',

        'trade/<region:[\w-]+>/<category:[\w-]+>/page<page:\d+>' => 'trade/trade/list',
        'trade/<region:[\w-]+>/page<page:\d+>' => 'trade/trade/list',
        'trade/page<page:\d+>' => 'trade/trade/list',

        'trade/<region:[\w-]+>/<category:[\w-]+>' => 'trade/trade/list',
        'trade/<region:[\w-]+>' => 'trade/trade/list',
        'trade' => 'trade/trade/list',

        'order/cart/successfully=id<id:\d+>' => 'order/cart/successfully',
        'order/cart' => 'user/order/cart',

        // Статьи
        'articles/<id:\d+>-<slug:[\w-]+>' => 'article/article/view',
        'articles/<category:[\w-]+>/page<page:\d+>' => 'article/article/list',
        'articles/page<page:\d+>' => 'article/article/list',
        'articles/<category:[\w-]+>' => 'article/article/list',
        'articles' => 'article/article/list',
        // Новости
        'news/<id:\d+>-<slug:[\w-]+>' => 'news/news/view',
        'news/<category:[\w-]+>/page<page:\d+>' => 'news/news/list',
        'news/page<page:\d+>' => 'news/news/list',
        'news/<category:[\w-]+>' => 'news/news/list',
        'news' => 'news/news/list',
        // Новости компаний
        'cnews/<id:\d+>-<slug:[\w-]+>' => 'cnews/cnews/view',
        'cnews/<category:[\w-]+>/page<page:\d+>' => 'cnews/cnews/list',
        'cnews/page<page:\d+>' => 'cnews/cnews/list',
        'cnews/<category:[\w-]+>' => 'cnews/cnews/list',
        'cnews' => 'cnews/cnews/list',
        // Бренды
        'brand/<id:\d+>-<slug:[\w-]+>' => 'brand/brand/view',
        'brand/<category:[\w-]+>/page<page:\d+>' => 'brand/brand/list',
        'brand/page<page:\d+>' => 'brand/brand/list',
        'brand/<category:[\w-]+>' => 'brand/brand/list',
        'brand' => 'brand/brand/list',
        // Выставки
        'expo/<id:\d+>-<slug:[\w-]+>' => 'expo/expo/view',
        'expo/<category:[\w-]+>/page<page:\d+>' => 'expo/expo/list',
        'expo/page<page:\d+>' => 'expo/expo/list',
        'expo/<category:[\w-]+>' => 'expo/expo/list',
        'expo' => 'expo/expo/list',

        'user/dialogs' => 'user/message/dialogs',

        '<_c:[\w\-]+>' => '<_c>/index',
        '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
        '<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_c>/<_a>',
        '<_c:[\w\-]+>/<_a:[\w-]+>' => '<_c>/<_a>',
    ],
];