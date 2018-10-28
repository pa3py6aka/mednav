<?php

namespace frontend\controllers\order;


use core\entities\Order\UserOrder;
use core\repositories\OrderRepository;
use yii\base\Controller;

class CartController extends Controller
{
    public function actionSuccessfully()
    {
        $id = \Yii::$app->request->get('id');
        $fullOrder = (new OrderRepository())->getFullOrder($id);

        return $this->render('successfully', [
            'fullOrder' => $fullOrder,
        ]);
    }
}