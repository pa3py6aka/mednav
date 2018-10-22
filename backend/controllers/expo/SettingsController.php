<?php

namespace backend\controllers\expo;


use core\forms\manage\Expo\ExpoSettingsIndexForm;
use core\forms\manage\Expo\ExpoSettingsMainForm;
use Yii;
use yii\web\Controller;

class SettingsController extends Controller
{
    public function actionMain()
    {
        $form = new ExpoSettingsMainForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            if (Yii::$app->settings->saveForm($form)) {
                Yii::$app->session->setFlash("success", "Настройки успешно сохранены");
            }
        }

        return $this->render('index', ['model' => $form, 'tab' => 'main']);
    }

    public function actionIndexPage()
    {
        $form = new ExpoSettingsIndexForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            if (Yii::$app->settings->saveForm($form)) {
                Yii::$app->session->setFlash("success", "Настройки успешно сохранены");
            }
        }

        return $this->render('index', ['model' => $form, 'tab' => 'index-page']);
    }
}