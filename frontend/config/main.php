<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'name' => 'Mednav.ru',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'common\bootstrap\SetUp'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'class' => \core\components\YiiUser::class,
            'identityClass' => \core\entities\User\User::class,
            'enableAutoLogin' => true,
            'loginUrl' => ['/auth/auth/login'],
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
            'on afterLogin' => function (\yii\web\UserEvent $event) {
                (new \core\components\Cart\Cart($event->identity))->migrateFromCookiesToDatabase();
            }
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'mednav-frontend',
            //'class' => 'yii\redis\Session',
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
            'errorAction' => 'main/error',
        ],
        'assetManager' => [
            'appendTimestamp' => true,
            /*'bundles' => [
                'yii\bootstrap\BootstrapAsset' => [
                    'basePath' => '@webroot/bootstrap',
                    'baseUrl' => '@web/bootstrap',
                    'css' => ['css/bootstrap.css'],
                ],
            ],//*/
        ],
        'reCaptcha' => [
            'name' => 'reCaptcha',
            'class' => 'himiklab\yii2\recaptcha\ReCaptcha',
            'siteKey' => $params['ReCaptchaSiteKey'],
            'secret' => $params['ReCaptchaSecret'],
        ],
        'backendUrlManager' => require __DIR__ . '/../../backend/config/urlManager.php',
        'frontendUrlManager' => require __DIR__ . '/urlManager.php',
        'urlManager' => function () {
            return Yii::$app->get('frontendUrlManager');
        },
    ],
    'params' => $params,
    'on afterRequest' => function($event) {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->redis->set('online-' . Yii::$app->user->id, time());
        }
    },
];
