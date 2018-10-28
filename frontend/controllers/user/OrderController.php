<?php

namespace frontend\controllers\user;


use core\access\Rbac;
use core\components\Cart\actions\AddToCartAction;
use core\components\Cart\Cart;
use core\entities\Order\Order;
use core\forms\OrderForm;
use core\readModels\OrderReadRepository;
use core\useCases\OrderService;
use Yii;
use yii\base\Module;
use yii\base\UserException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class OrderController extends Controller
{
    private $service;
    private $_user;

    public function __construct(string $id, Module $module, OrderService $service, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
        $this->_user = Yii::$app->user->identity;
        $this->view->params['user'] = $this->_user;
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'except' => ['add-to-cart', 'cart', 'order'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [Rbac::ROLE_USER],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [

                ]
            ]
        ];
    }

    public function actions()
    {
        return [
            'add-to-cart' => AddToCartAction::class,
        ];
    }

    public function actionCart()
    {
        $cartItems = (new Cart(Yii::$app->user->identity))->getItemsForOrder();
        $form = new OrderForm(Yii::$app->user->identity);

        return $this->render('cart', [
            'cartItems' => $cartItems,
            'orderForm' => $form,
        ]);
    }

    public function actionOrder()
    {
        $form = new OrderForm();
        $form->load(Yii::$app->request->post());

        if ($form->validate()) {
            try {
                $fullOrder = $this->service->create($form);
                //Yii::$app->session->setFlash("success", "Ваш заказ " . implode(', ', $numbers['numbers']) . " успешно оформлен.");
                return $this->redirect(['/order/cart/successfully', 'id' => $fullOrder->id]);
                //return $this->redirect(Yii::$app->user->isGuest ? ['/trade/trade/list', 'region' => Yii::$app->session->get('geo', 'all')] : ['orders']);
            } catch (\DomainException $e) {
                throw new UserException($e->getMessage());
            }
        }

        throw new UserException(implode("<br>", $form->getFirstErrors()));
    }

    public function actionOrders()
    {
        $provider = (new OrderReadRepository())->getOrdersFor(Yii::$app->user->identity);

        return $this->render('orders', [
            'provider' => $provider,
        ]);
    }

    public function actionView($id)
    {
        $order = $this->getOrder($id);
        if ($order->status == Order::STATUS_NEW && $order->user_id != Yii::$app->user->id) {
            $order->updateAttributes(['status' => Order::STATUS_NEW_VIEWED]);
        }
        $orderItemsProvider = (new OrderReadRepository())->getOrderItems($order);

        return $this->render('view', [
            'order' => $order,
            'orderItemsProvider' => $orderItemsProvider,
        ]);
    }

    private function getOrder($id): Order
    {
        if (!$order = Order::findOne($id)) {
            throw new NotFoundHttpException("Заказ не найден");
        }
        return $order;
    }
}