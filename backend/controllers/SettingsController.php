<?php

namespace backend\controllers;


use core\entities\Currency;
use core\forms\manage\CurrenciesForm;
use Yii;
use yii\web\Controller;

class SettingsController extends Controller
{
    public function actionCurrencies($for = Currency::MODULE_BOARD)
    {
        $form = new CurrenciesForm($for);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $form->save();
                Yii::$app->session->setFlash("success", "Данные обновлены");
                return $this->refresh();
            } catch (\DomainException $e) {
                Yii::$app->session->setFlash("error", $e->getMessage());
                return $this->refresh();
            }
        }

        $view = '@backend/views/' . Currency::getModuleName($for) . '/settings/index';
        $tab = '@backend/views/settings/_currencies';

        return $this->render($view, ['model' => $form, 'tab' => $tab]);
    }
}