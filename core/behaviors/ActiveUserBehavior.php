<?php

namespace core\behaviors;


use Yii;
use yii\base\Behavior;
use yii\base\UserException;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;

class ActiveUserBehavior extends Behavior
{
    public $actions = [];
    public $onlyForCompany = false;

    public function events()
    {
        return [Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
    }

    public function beforeAction($event)
    {
        $action = $event->action->id;
        $user = Yii::$app->user->identity;

        if (!$this->actions || isset($this->actions[$action])) {
            if (Yii::$app->user->isGuest) {
                return Yii::$app->controller->redirect(Yii::$app->user->loginUrl);
            }

            try {
                if ($this->onlyForCompany && !$user->isCompany()) {
                    throw new UserException("Этот раздел доступен только для компаний.");
                } else if ($user->isProfileEmpty()) {
                    throw new UserException("Для начала работы, заполните форму <a href=\"" . Url::to(['/user/account/profile']) . "\">вашего профиля</a>
            " . ($user->isCompany() ? " и " . Html::a('данные о компании', ['/user/account/company']) : "" ));
                }
            } catch (UserException $e) {
                return Yii::$app->response->redirect(['/user/account/index']);
            }
        }

        return $event->isValid;
    }
}