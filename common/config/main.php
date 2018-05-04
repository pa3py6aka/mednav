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
            'class' => \yii\redis\Cache::class,
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
        'formatter' => [
            'dateFormat' => 'php: d.m.Y',
            'datetimeFormat' => 'php: d.m.Y H:i',
            'timeFormat' => 'php: H:i',
            'booleanFormat' => ['<i class="fa fa-remove text-red"></i>', '<i class="fa fa-check text-green"></i>']
        ]
    ],
];
