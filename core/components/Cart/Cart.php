<?php

namespace core\components\Cart;


use core\entities\Order\CartItem;
use core\entities\Trade\Trade;
use core\entities\User\User;
use core\helpers\PriceHelper;
use core\services\TransactionManager;
use Yii;
use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class Cart
{
    /* @var User|null */
    private $user;

    /* @var CartItem[]|array */
    private $_items = null;

    public function __construct(User $user = null)
    {
        $this->user = $user;
    }

    public function add($productId, $amount): int
    {
        if ($this->user) {
            return $this->saveToDatabase($productId, $amount);
        } else {
            return $this->saveToCookies($productId, $amount);
        }
    }

    public function removeItem($productId): ?int
    {
        $items = $this->getItems();
        if (isset($items[$productId])) {
            if ($this->user) {
                $items[$productId]->delete();
                $this->_items = null;
                return $this->getItemsCount();
            } else {
                unset($items[$productId]);
                $cookies = Yii::$app->response->cookies;
                $cookies->add(new \yii\web\Cookie([
                    'name' => 'cartItems',
                    'value' => Json::encode($items),
                ]));
                return count($items);
            }
        }
        return null;
    }

    public function clear(): void
    {
        if ($this->user) {
            CartItem::deleteAll(['user_id' => $this->user->id]);
        } else {
            Yii::$app->response->cookies->remove('cartItems');
        }
    }

    public function getItemsCount(): int
    {
        return count($this->getItems());
    }

    public function getItemsForOrder(): array
    {
        $items = $this->getItems(true);
        $result = [];
        foreach ($items as $cartItem) {
            $result[$cartItem->trade->company_id][] = $cartItem;
        }
        return $result;
    }

    public function migrateFromCookiesToDatabase()
    {
        $cookieItems = $this->getItemsFromCookies();
        if (count($cookieItems)) {
            (new TransactionManager())->wrap(function () use ($cookieItems) {
                CartItem::deleteAll(['user_id' => $this->user->id]);
                foreach ($cookieItems as $cartItem) {
                    $cartItem->save();
                }
            });
        }
        Yii::$app->response->cookies->remove('cartItems');
    }

    public static function getItemPrice($price, $amount)
    {
        return PriceHelper::normalize($price * $amount);
    }

    private function saveToDatabase($productId, $amount): int
    {
        if (!$cartItem = CartItem::find()->where(['user_id' => $this->user->id, 'trade_id' => $productId])->limit(1)->one()) {
            $cartItem = CartItem::create($this->user->id, $productId, $amount);
        } else {
            $cartItem->amount = $cartItem->amount + $amount;
        }
        if ($cartItem->amount < 1) {
            $cartItem->amount = 1;
        }
        if (!$cartItem->save()) {
            throw new \RuntimeException("Ошибка записи в базу.");
        }
        return CartItem::find()->where(['user_id' => $this->user->id])->count();
    }

    private function saveToCookies($productId, $amount): int
    {
        $cartItems = $this->getItems();
        if (isset($cartItems[$productId])) {
            $cartItems[$productId]['amount'] = $cartItems[$productId]['amount'] + $amount;
        } else {
            $cartItems[$productId] = [
                'amount' => $amount,
                'company_id' => Trade::find()->select('company_id')->where(['id' => $productId])->scalar(),
            ];
        }

        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name' => 'cartItems',
            'value' => Json::encode($cartItems),
        ]));

        return count($cartItems);
    }

    /**
     * @param bool $eager Жадная загрузка данных по товару(для страницы заказа)
     * @return CartItem[]|array
     */
    private function getItems($eager = false): array
    {
        if ($this->_items === null) {
            if ($this->user) {
                $query = CartItem::find()
                    ->where(['user_id' => $this->user->id])
                    ->indexBy('trade_id');
                if ($eager) {
                    $query->with('trade.company.deliveries.delivery', 'trade.mainPhoto', 'trade.userCategory');
                }
                $this->_items = $query->all();
            } else {
                $this->_items = $this->getItemsFromCookies();
            }
        }
        return $this->_items;
    }

    /**
     * @return CartItem[]|array
     */
    private function getItemsFromCookies(): array
    {
        $cookieItems = Json::decode(Yii::$app->request->cookies->getValue('cartItems', '[]'));
        $items = [];
        foreach ($items as $productId => $item) {
            $items[$productId] = CartItem::create($this->user ? $this->user->id : null, $productId, $item['amount']);
        }
        return $items;
    }
}