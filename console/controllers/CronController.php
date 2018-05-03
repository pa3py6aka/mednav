<?php

namespace console\controllers;


use Yii;
use yii\console\Controller;
use yii\helpers\FileHelper;

class CronController extends Controller
{
    public function actionTempCleaner()
    {
        $files = FileHelper::findFiles(Yii::getAlias("@frontend/web/tmp"));
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) != 'gitignore' && filectime($file) < (time() - 86400)) {
                unlink($file);
            }
        }
    }
}