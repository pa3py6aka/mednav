<?php

use yii\helpers\ReplaceArrayValue;

return [
    'id' => 'app-common-tests',
    'basePath' => dirname(__DIR__),
    'components' => [
        'user' => [
            'class' => \core\components\YiiUser::class,
            'identityClass' => \core\entities\User\User::class,
            //'enableAutoLogin' => true,
            'identityCookie' => new ReplaceArrayValue(['name' => '_identity', 'httpOnly' => true]),
        ],
        /*'request' => [
            'cookieValidationKey' => 'test',
        ],*/
    ],
];
