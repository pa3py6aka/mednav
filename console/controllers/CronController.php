<?php

namespace console\controllers;


use core\components\Settings;
use core\entities\Board\Board;
use core\helpers\Pluralize;
use core\jobs\SendMailJob;
use core\useCases\manage\Board\BoardManageService;
use Yii;
use yii\console\Controller;
use yii\helpers\FileHelper;

class CronController extends Controller
{
    /**
     * Удаляет временные файлы созданные во время загрузки изображений
     */
    public function actionTempCleaner(): void
    {
        $files = FileHelper::findFiles(Yii::getAlias("@frontend/web/tmp"));
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) !== 'gitignore' && filectime($file) < (time() - 86400)) {
                unlink($file);
            }
        }
    }

    /**
     * Архивирует объявления по истечении их срока публикации
     */
    public function actionArchivator(): void
    {
        $service = Yii::createObject(BoardManageService::class);
        $n = 0;
        foreach (Board::find()->where(['status' => Board::STATUS_ACTIVE])->andWhere(['<=', 'active_until', time()])->each() as $board) {
            $service->archive($board);
            $n++;
        }
        echo 'В архив отправлено ' . Pluralize::get($n, 'объявление', 'объявления', 'объявлений') . PHP_EOL;
    }

    public function actionBoardExtendNotificator(): void
    {
        $queue = Yii::$app->queue;
        $redis = Yii::$app->redis;
        foreach (Board::find()->toExtend()->each() as $board) {
            $redisKey = 'board-m-e-' . $board->id;
            if ($board->author->email && $board->active_until > time() && !$redis->get($redisKey)) {
                $queue->push(new SendMailJob([
                    'view' => 'board/must-extend-notification',
                    'params' => ['board' => $board],
                    'to' => $board->author->email,
                    'subject' => '[' . Yii::$app->settings->get(Settings::GENERAL_EMAIL_FROM) . '] Уведомление - заканчивается срок публикации Вашего объявления.',
                ]));
                $redis->setex($redisKey, $board->active_until - time() + 3610, 1);
                echo "отправлено: {$board->id}" . PHP_EOL;
            }
        }
    }
}