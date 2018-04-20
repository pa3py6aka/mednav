<?php

namespace console\controllers;

use core\access\AuthorRule;
use Yii;
use yii\console\Controller;
use core\access\Rbac;

/**
 * RBAC generator
 */
class RbacController extends Controller
{
    /**
     * Generates roles
     */
    public function actionInit()
    {
        $this->stdout('RBAC Init' . PHP_EOL);
        $auth = Yii::$app->getAuthManager();
        $auth->removeAll();

        /* Создаём правила */
        $authorRule = new AuthorRule();
        $auth->add($authorRule);

        /* Создаём permissions */
        $manageOwn = $auth->createPermission(Rbac::PERMISSION_OWN_MANAGE);
        $manageOwn->description = 'Manage Own';
        $manageOwn->ruleName = $authorRule->name;
        $auth->add($manageOwn);

        $manage = $auth->createPermission(Rbac::PERMISSION_MANAGE);
        $manage->description = 'Manage';
        $auth->add($manage);
        $auth->addChild($manageOwn, $manage);

        /* Создаём Роли */
        $user = $auth->createRole(Rbac::ROLE_USER);
        $user->description = 'User';
        $auth->add($user);
        $auth->addChild($user, $manageOwn);

        $moderator = $auth->createRole(Rbac::ROLE_MODERATOR);
        $moderator->description = 'Moderator';
        $auth->add($moderator);
        $auth->addChild($moderator, $user);
        $auth->addChild($moderator, $manage);

        $admin = $auth->createRole(Rbac::ROLE_ADMIN);
        $admin->description = 'Admin';
        $auth->add($admin);
        $auth->addChild($admin, $moderator);

        $this->stdout('Done!' . PHP_EOL);
    }
}
