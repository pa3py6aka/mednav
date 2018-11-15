<?php

namespace frontend\widgets\message;


use core\useCases\DialogService;
use yii\base\Action;

class SendMessageAction extends Action
{
    /* @var DialogService */
    private $service;

    public function init()
    {
        $this->service = \Yii::createObject(DialogService::class);
        parent::init();
    }

    public function run()
    {
        $form = new NewMessageForm();

        if ($form->load(\Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->sendNew($form);
                return $this->controller->asJson(['result' => 'success']);
            } catch (\DomainException $e) {
                return $this->controller->asJson(['result' => 'error', 'message' => $e->getMessage()]);
            }
        }

        return $this->controller->asJson(['result' => 'error', 'message' => 'Какая-то ошибка...']);
    }
}