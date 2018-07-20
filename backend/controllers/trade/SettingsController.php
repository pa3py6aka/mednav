<?php

namespace backend\controllers\trade;


use core\forms\manage\Trade\TradeDeliveryForm;
use core\forms\manage\Trade\TradeSettingsIndexForm;
use core\forms\manage\Trade\TradeSettingsMainForm;
use core\forms\manage\Trade\TradeUoMsForm;
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

    public function actionUoms()
    {
        $form = new TradeUoMsForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $form->save();
            Yii::$app->session->setFlash("success", "Данные обновлены");
            $form = new TradeUoMsForm();
        }

        return $this->render('index', ['model' => $form, 'tab' => 'uoms']);
    }

    public function actionDelivery()
    {
        $form = new TradeDeliveryForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $form->save();
            Yii::$app->session->setFlash("success", "Данные обновлены");
            $form = new TradeDeliveryForm();
        }

        return $this->render('index', ['model' => $form, 'tab' => 'delivery']);
    }
}