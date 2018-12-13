<?php

namespace console\controllers;


use core\entities\Board\Board;
use core\helpers\Pluralize;
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
}