<?php

namespace core\components\Cart\widgets;


use core\components\Cart\CartAsset;
use yii\base\InvalidArgumentException;
use yii\base\Widget;

class OrderButtonWidget extends Widget
{
    public $productId;

    public function init()
    {
        parent::init();
        if (!$this->productId) {
            throw new InvalidArgumentException("Не определён ID продукта `productId`");
        }
        CartAsset::register($this->view);
    }

    public function run()
    {
        return $this->render('order-button', [
            'productId' => $this->productId,
        ]);
    }
}