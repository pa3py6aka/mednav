<?php

namespace backend\actions;


use Yii;

class ErrorAction extends \yii\web\ErrorAction
{
    /**
     * Runs the action.
     *
     * @return string result content
     */
    public function run()
    {
        if ($this->layout !== null) {
            $this->controller->layout = $this->layout;
        }

        if ($this->getExceptionCode() == 403) {
            $this->controller->layout = 'main-forbidden';
        }

        Yii::$app->getResponse()->setStatusCodeByException($this->exception);

        if (Yii::$app->getRequest()->getIsAjax()) {
            return $this->renderAjaxResponse();
        }

        return $this->renderHtmlResponse();
    }
}