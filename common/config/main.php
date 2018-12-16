<?php
return [
    'language' => 'ru',
    'timeZone' => 'Europe/Moscow',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'bootstrap' => [
        'queue',
    ],
    'vendorPath' => dirname(__DIR__, 2) . '/vendor',
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
        'queue' => [
            'class' => \yii\queue\db\Queue::class,
            'as log' => \yii\queue\LogBehavior::class,
            'db' => 'db',
            'tableName' => '{{%queue}}',
            'channel' => 'default',
            'mutex' => \yii\mutex\MysqlMutex::class,
            'ttr' => 5 * 60,
            'attempts' => 5,
        ],
        'filedb' => [
            'class' => \yii2tech\filedb\Connection::class,
            'path' => '@core/data',
            'format' => 'json',
        ],
        'formatter' => [
            'defaultTimeZone' => 'Europe/Moscow',
            'dateFormat' => 'php: d.m.Y',
            'datetimeFormat' => 'php: d.m.Y H:i',
            'timeFormat' => 'php: H:i',
            'booleanFormat' => ['<i class="fa fa-remove text-red"></i>', '<i class="fa fa-check text-green"></i>']
        ],
        'sqlite' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'sqlite:'. dirname(__DIR__, 2) . "/core/data" . "/data.db",
        ],
    ],
];
