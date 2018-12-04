<?php

namespace core\services;


use core\components\Settings;
use Yii;
use yii\base\Exception;

class Mailer
{
    public static function send($to, $subject, $view, $params = [], $from = null, $files = []): void
    {
        $from = $from ?: [Yii::$app->settings->get(Settings::GENERAL_EMAIL) => Yii::$app->settings->get(Settings::GENERAL_EMAIL_FROM)];
        if (
            !Yii::$app->mailer->compose($view, $params)
            ->setTo($to)
            ->setFrom($from)
            ->setSubject($subject)
            ->send()
        ) {
            //Yii::$app->errorHandler->logException(new Exception('Ошибка отправки письма'));
            throw new \DomainException('Ошибка отправки письма');
        }
    }
}