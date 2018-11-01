<?php

namespace frontend\controllers\order;


use core\components\Cart\Cart;
use core\entities\Order\UserOrder;
use core\repositories\OrderRepository;
use Yii;
use yii\base\Controller;
use yii\web\Response;

class CartController extends Controller
{
    public function actionSuccessfully()
    {
        $id = Yii::$app->request->get('id');
        $fullOrder = (new OrderRepository())->getFullOrder($id);

        return $this->render('successfully', [
            'fullOrder' => $fullOrder,
        ]);
    }

    public function actionRemoveItem()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $tradeId = (int) Yii::$app->request->post('tradeId');
        $cart = new Cart(Yii::$app->user->identity);
        $count = $cart->removeItem($tradeId);
        if ($count === null) {
            return ['result' => 'error', 'message' => 'Товар в корзине не найден.'];
        }
        return ['result' => 'success', 'count' => $count];
    }
}