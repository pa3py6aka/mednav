<?php

namespace backend\controllers\trade;


use core\forms\manage\Trade\TradeSettingsIndexForm;
use core\forms\manage\Trade\TradeSettingsMainForm;
use Yii;
use yii\web\Controller;

class SettingsController extends Controller
{
    public function actionMain()
    {
        $form = new TradeSettingsMainForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            if (Yii::$app->settings->saveForm($form)) {
                Yii::$app->session->setFlash("success", "Настройки успешно сохранены");
            }
        }

        return $this->render('index', ['model' => $form, 'tab' => 'main']);
    }

    public function actionIndexPage()
    {
        $form = new TradeSettingsIndexForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            if (Yii::$app->settings->saveForm($form)) {
                Yii::$app->session->setFlash("success", "Настройки успешно сохранены");
            }
        }

        return $this->render('index', ['model' => $form, 'tab' => 'index-page']);
    }

    public function actionCurrencies()
    {

    }
}