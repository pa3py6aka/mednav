<?php

namespace backend\controllers\blocks;


use core\entities\Context;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class ContextController extends Controller
{
    public function actionIndex()
    {
        $blocks = Context::find()->all();

        $html = Yii::$app->request->post('html');
        if (is_array($html)) {
            $enable = Yii::$app->request->post('enable');
            foreach ($html as $id => $value) {
                $block = Context::findOne($id);
                $block->enable = ArrayHelper::getValue($enable, $id, $block->enable);
                $block->html = $value;
                $block->save();
            }
            return $this->redirect(['index']);
        }

        return $this->render('index', [
            'blocks' => $blocks,
        ]);
    }
}