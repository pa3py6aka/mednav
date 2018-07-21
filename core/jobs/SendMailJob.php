<?php

namespace core\jobs;


use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\queue\Queue;

class SendMailJob extends BaseObject implements JobInterface
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
        echo "start" . PHP_EOL;
        Yii::$app->mailer->compose($this->view, $this->params)
            ->setSubject($this->subject)
            ->setFrom([Yii::$app->params['robotEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->to)
            ->send();
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