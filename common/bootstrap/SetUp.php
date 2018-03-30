<?php

namespace common\bootstrap;


use yii\base\BootstrapInterface;
use yii\rbac\ManagerInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = \Yii::$container;

        $container->setSingleton(ManagerInterface::class, function () use ($app) {
            return $app->authManager;
        });
    }
}