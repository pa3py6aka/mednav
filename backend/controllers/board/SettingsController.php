<?php

namespace backend\controllers\board;


use core\forms\manage\Board\BoardSettingsIndexForm;
use core\forms\manage\Board\BoardSettingsMainForm;
use Yii;
use yii\web\Controller;

class SettingsController extends Controller
{
    public function actionMain()
    {
        $form = new BoardSettingsMainForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            if (Yii::$app->settings->saveForm($form)) {
                Yii::$app->session->setFlash("success", "Настройки успешно сохранены");
                //return $this->render('main', ['model' => $form]);
            }
        }

        return $this->render('index', ['model' => $form, 'tab' => 'main']);
    }

    public function actionIndexPage()
    {
        $form = new BoardSettingsIndexForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            if (Yii::$app->settings->saveForm($form)) {
                Yii::$app->session->setFlash("success", "Настройки успешно сохранены");
                //return $this->render('main', ['model' => $form]);
            }
        }

        return $this->render('index', ['model' => $form, 'tab' => 'index-page']);
    }


}