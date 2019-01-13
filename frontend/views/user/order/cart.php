<?php
use yii\web\JqueryAsset;
use yii\helpers\Html;
use core\components\Cart\Cart;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this \yii\web\View */
/* @var $cartItems array  */
/* @var $orderForm \core\forms\OrderForm */

/* @var $order \core\entities\Order\CartItem[] */
/* @var $cartItem \core\entities\Order\CartItem */

\yii\jui\JuiAsset::register($this);
$this->registerJsFile('@web/js/cart-order.js?v=' . filemtime(Yii::getAlias('@webroot/js/cart-order.js')), ['depends' => JqueryAsset::class]);
$orderNumber = 1;

?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <ul class="breadcrumb">
            <li><a href="/">Главная</a></li>
            <li><a href="#">Оформление заказа</a></li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-md-10 col-md-offset-1" id="cart-full-block">
        <h1>Корзина</h1>

        <?php foreach ($cartItems as $order): ?>
        <div data-type="order-row">
            <div class="cart-order"><div class="cart-order-number">Заказ № <?= $orderNumber ?></div>
                <div class="cart-vendor">
                    Продавец:
                    <a href="<?= current($order)->trade->company->getUrl() ?>" target="_blank"><?= current($order)->trade->company->getFullName() ?></a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1 col-sm-1 col-xs-12">
                    <div class="cart-title">Наименование:</div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12"></div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                    <div class="cart-title">Кол-во:</div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                    <div class="cart-title">Цена:</div>
                </div>
                <div class="col-md-2 col-sm-6 col-xs-12">
                    <div class="cart-title">Сумма:</div>
                </div>
                <div class="col-md-1 col-sm-6 col-xs-12"></div>
            </div>

            <?php foreach ($order as $cartItem): ?>
                <div class="row" data-row-item="<?= $cartItem->trade_id ?>">
                    <div class="col-md-1 col-sm-1 col-xs-12">
                        <div>&nbsp;</div>
                        <div class="cart-item-img">
                            <a href="<?= $cartItem->trade->getUrl() ?>" target="_blank">
                                <img src="<?= $cartItem->trade->getMainPhotoUrl() ?>" style="width:75px;">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div>&nbsp;</div>
                        <div class="cart-item">
                            <a href="<?= $cartItem->trade->getUrl() ?>" target="_blank"><?= Html::encode($cartItem->trade->name) ?></a>
                        </div>
                        <div class="desc-col">Артикул: <?= Html::encode($cartItem->trade->code) ?></div>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <div>&nbsp;</div>
                        <div class="input-group spinner">
                            <input name="OrderForm[amounts][<?= $orderNumber ?>][<?= $cartItem->trade_id ?>]" type="text" class="form-control item-amount-input" data-trade-id="<?= $cartItem->trade_id ?>" value="<?= $cartItem->amount ?>" min="0" max="9999999">
                            <div class="input-group-btn-vertical">
                                <button class="btn btn-default" type="button"><i class="fa fa-caret-up"></i></button>
                                <button class="btn btn-default" type="button"><i class="fa fa-caret-down"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <div>&nbsp;</div>
                        <div
                            data-trade-id="<?= $cartItem->trade_id ?>"
                            data-type="item-price"
                            data-price="<?= $cartItem->trade->price ?: 0 ?>"
                            data-currency="<?= $cartItem->trade->userCategory->currency->sign ?>"
                            data-uom="<?= $cartItem->trade->userCategory->uom->sign ?>"
                        >
                            <?= $cartItem->trade->getPriceString() ?>/<?= $cartItem->trade->getUomString() ?>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <div>&nbsp;</div>
                        <div data-trade-id="<?= $cartItem->trade_id ?>" data-type="item-sum">
                            <?= Cart::getItemPrice($cartItem->trade->price, $cartItem->amount) . ' ' . $cartItem->trade->getCurrencyString() ?>
                        </div>
                    </div>
                    <div class="col-md-1 col-sm-6 col-xs-12">
                        <div>&nbsp;</div>
                        <div><a href="#" data-button="cart-remove-item" data-trade-id="<?= $cartItem->trade_id ?>" title="Удалить"><i class="glyphicon glyphicon-remove city-icon-grey"></i></a></div>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="row">
                <?php if (current($order)->trade->company->deliveries): ?>
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="cart-delivery">
                        <select class="form-control input-md order-delivery-selector" style="width:auto;" name="OrderForm[deliveries][<?= $orderNumber ?>]">
                            <option value>Доставка* (выбрать)</option>
                            <?php foreach (current($order)->trade->company->deliveries as $companyDelivery): ?>
                                <option value="<?= $companyDelivery->delivery_id ?>"><?= $companyDelivery->delivery->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <?php endif; ?>
                <div class="col-md-7 col-sm-5 col-xs-12">
                    <div class="cart-total-price"></div>
                </div>
                <div class="col-md-1 col-sm-1 hidden-xs">
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="cart-delivery">
                        <div class="kt-item-delivery">
                            <input type="checkbox" id="hd-<?= $orderNumber ?>" class="hide"/>
                            <label for="hd-<?= $orderNumber ?>" >Комментарий к заказу для <?= current($order)->trade->company->getFullName() ?></label>
                            <div>
                                <div class="input-group">
                                    <textarea name="OrderForm[comments][<?= $orderNumber ?>]" class="form-control order-comment-textarea" rows="3" cols="40" placeholder="Комментарий к заказу"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
        </div>
        <?php $orderNumber++; ?>
        <?php endforeach; ?>

        <?php if (count($cartItems)): ?>
        <div class="row">
            <div class="cart-contact-form">
                <?php $form = ActiveForm::begin([
                    'action' => ['/user/order/order'],
                    'layout' => 'horizontal',
                    'id' => 'order-form',
                    'fieldConfig' => [
                        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                        'horizontalCssClasses' => [
                            'label' => 'col-md-2',
                            'offset' => '',
                            'wrapper' => 'col-md-10',
                        ],
                    ],
                ]) ?>

                <div class="col-md-10 col-md-offset-1">
                    <p>Данные для заказа:</p>

                    <?= $form->field($orderForm, 'name')->textInput(['placeholder' => 'Введите Имя или Название компании']) ?>

                    <?= $form->field($orderForm, 'phone')->textInput(['placeholder' => 'Введите Телефон']) ?>

                    <?= $form->field($orderForm, 'email')->textInput(['placeholder' => 'Введите E-mail']) ?>

                    <?= $form->field($orderForm, 'address')->textInput(['placeholder' => 'Город, улица, дом, (кв/офис)']) ?>

                    <?= $form->field($orderForm, 'captcha')->widget(Captcha::class, [
                        'captchaAction' => ['/auth/auth/captcha'],
                        'imageOptions' => ['style' => 'cursor:pointer;', 'title' => 'Обновить картинку'],
                        'options' => ['placeholder' => 'Введите код с картинки', 'class' => 'form-control'],
                        'template' => '<label class="label col-md-2">{image}</label><div class="col-md-10 no-padding">{input}</div>',

                    ])->label('') ?>
                </div>

                <div class="col-md-12">
                    <button type="button" class="btn btn-lg center-block cart-btn-order" id="order-form-submit-btn">
                        <i class="glyphicon glyphicon-ok btn-md"></i> Оформить заказ
                    </button>
                </div>
                <div class="col-xs-offset-2 col-xs-10 private-note">
                    <div class="checkbox">
                        <?= $form->field($orderForm, 'agreement')
                            ->checkbox()
                            ->label('Подтверждаю свое согласие на обработку персональных данных в соответствии с <a href="/pages/terms-of-use" title="Пользовательское соглашение" target="_blank">пользовательским соглашением</a> и <a href="/pages/privacy-policy" title="Политика конфиденциальности" target="_blank">политикой конфиденциальности</a>') ?>
                    </div>
                </div>
                <?php ActiveForm::end() ?>
            </div>
        </div>
        <?php else: ?>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <P>Ваша корзина пуста</P>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<div id="dialog">
    Удалить товар из корзины?
</div>


