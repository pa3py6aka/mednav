<?php
return [
    'language' => 'ru',
    'timeZone' => 'Europe/Moscow',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => \yii\rbac\DbManager::class,
        ],
        'settings' => [
            'class' => \core\components\SettingsManager::class,
        ],
        'redis' => [
            'class' => \yii\redis\Connection::class,
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
        ],
        'filedb' => [
            'class' => \yii2tech\filedb\Connection::class,
            'path' => '@core/data',
            'format' => 'json',
        ],
    ],
];
