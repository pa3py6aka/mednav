<?php

namespace core\services;


use core\components\Settings;
use Yii;

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
            throw new \DomainException("Ошибка отправки письма");
        }
    }
}