<?php

namespace frontend\widgets;


use core\entities\Trade\TradeUserCategory;
use core\forms\manage\Trade\TradeManageForm;
use yii\base\InvalidArgumentException;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\JsExpression;

class WholesalesFormWidget extends Widget
{
    /* @var TradeManageForm */
    public $model;

    public $userId;

    public function init()
    {
        parent::init();
        if (!$this->model) {
            throw new InvalidArgumentException("Не установлена модель формы `model`");
        }
        $this->userId = $this->userId ?: \Yii::$app->user->id;

        $categoriesWholesales = ArrayHelper::map(TradeUserCategory::find()->with('currency', 'uom')->where(['user_id' => $this->userId])->all(), 'id', function (TradeUserCategory $item) {
            return [
                'wholesale' => $item->wholesale,
                'currency' => $item->currency->sign,
                'uom' => $item->uom->sign,
            ];
        });
        $this->view->registerJsVar('_categoriesWholesales', new JsExpression(Json::encode($categoriesWholesales)));
        $this->view->registerJs($this->getJs());
    }

    public function run()
    {
        return $this->render('wholesale-field', ['model' => $this->model]);
    }

    public function getJs()
    {
        $js = <<<JS
$('#category-selector').on('change', function() {
    var id = $(this).val(),
        block = $('#wholesales-block'),
        currencies = $('.whole-sile-price'),
        priceCurrency = $('#price-currency'),
        from = $('.whole-sile-from');
    if (id && _categoriesWholesales[id]['wholesale']) {
        block.removeClass('hidden');
        currencies.text(_categoriesWholesales[id]['currency'] + ',');
        priceCurrency.text(_categoriesWholesales[id]['currency']);
        from.attr('placeholder', _categoriesWholesales[id]['uom']);
    } else {
        block.addClass('hidden');
    }
});
JS;
        return $js;
    }
}