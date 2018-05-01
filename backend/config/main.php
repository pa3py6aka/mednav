<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'name' => 'Control Panel',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log', 'common\bootstrap\SetUp'],
    'modules' => [],
    'components' => [
        'assetManager' => [
            'appendTimestamp' => true,
        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'class' => \core\components\YiiUser::class,
            'identityClass' => \core\entities\User\User::class,
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'frontendUrlManager' => require __DIR__ . '/../../frontend/config/urlManager.php',
        'backendUrlManager' => require __DIR__ . '/urlManager.php',
        'urlManager' => function () {
            return Yii::$app->get('backendUrlManager');
        },
    ],
    'params' => $params,

    'as access' => [
        'class' => \yii\filters\AccessControl::class,
        'except' => ['site/login', 'site/error', 'site/logout'],
        'rules' => [
            [
                'allow' => true,
                'roles' => [\core\access\Rbac::ROLE_ADMIN],
            ],
        ],
    ],
];
