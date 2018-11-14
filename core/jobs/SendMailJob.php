<?php

namespace core\jobs;


use core\components\Settings;
use core\services\Mailer;
use Yii;
use yii\base\BaseObject;
use yii\queue\Queue;
use yii\queue\RetryableJobInterface;

class SendMailJob extends BaseObject implements RetryableJobInterface
{
    /* @var string mail view template */
    public $view;

    /* @var array params to mail view */
    public $params = [];

    /* @var string|array to email */
    public $to;

    /* @var string email subject */
    public $subject;

    //public $files;

    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        /*Yii::$app->mailer->compose($this->view, $this->params)
            ->setSubject($this->subject)
            ->setFrom([Yii::$app->settings->get(Settings::GENERAL_EMAIL) => Yii::$app->settings->get(Settings::GENERAL_EMAIL_FROM)])
            ->setTo($this->to)
            ->send();*/

        Mailer::send(
            $this->to,
            $this->subject,
            $this->view,
            $this->params
        );
    }

    public function getTtr()
    {
        return 15 * 60;
    }

    public function canRetry($attempt, $error)
    {
        return $attempt < 3;
    }
}