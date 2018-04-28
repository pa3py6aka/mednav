<?php

namespace core\services;


class Mailer
{
    public static function send($to, $subject, $view, $params = [], $from = null, $files = []): void
    {
        $from = $from ?: [\Yii::$app->params['supportEmail'] => \Yii::$app->name . ' robot'];
        if (
            !\Yii::$app->mailer->compose($view, $params)
            ->setTo($to)
            ->setFrom($from)
            ->setSubject($subject)
            ->send()
        ) {
            throw new \DomainException("Ошибка отправки письма");
        }
    }
}