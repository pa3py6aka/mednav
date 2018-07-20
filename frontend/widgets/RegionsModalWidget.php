<?php

namespace frontend\widgets;


use core\entities\Board\BoardCategory;
use core\entities\Geo;
use core\helpers\BoardHelper;
use core\helpers\CompanyHelper;
use core\helpers\TradeHelper;
use Yii;
use yii\base\InvalidArgumentException;
use yii\base\Widget;
use yii\helpers\Html;

class RegionsModalWidget extends Widget
{
    const CACHE_KEY = 'regionsModal';

    /**
     * @var string|null Определяет где используется модальное окно:
     *       null     - выбор региона в формах,
     *       delivery - выбор нескольких регионов в настройках доставки товаров в ППУ
     *       board    - в листинге доски объявлений
     *       company  - в листинге компаний
     *       trade    - в листинге товаров
     */
    public $type = null;

    /* @var BoardCategory|null */
    public $category;

    /* @var array|null Массив с айдишниками отмеченных регионов для type => delivery */
    public $selectedIds;

    /* @var int|null ID типа доставки для type => delivery */
    public $deliveryId;

    public function init()
    {
        parent::init();
        if ($this->type === null) {
            $this->view->registerJs($this->getJs());
        }
    }

    public function run()
    {
        return $this->render('regions-modal', [
            'widget' => $this,
            'countries' => $this->getCategoriesArray(),
        ]);
    }

    private function getCategoriesArray(): array
    {
        return Yii::$app->cache->getOrSet(self::CACHE_KEY, function () {
            $countries = Geo::find()->countries()->active()->all();
            $countriesArray = [];
            foreach ($countries as $country) {
                $countriesArray[$country->id]['country'] = $country;
                if (!isset($countriesArray[$country->id]['popular'])) {
                    $countriesArray[$country->id]['popular'] = $country->getDescendants()->active()->popular()->all();
                }

                $countriesArray[$country->id]['regions'] = [];
                foreach ($country->getChildren()->active()->all() as $region) {
                    $countriesArray[$country->id]['regions'][$region->id]['region'] = $region;
                    $countriesArray[$country->id]['regions'][$region->id]['cities'] = [];
                    foreach ($region->getChildren()->active()->all() as $city) {
                        $countriesArray[$country->id]['regions'][$region->id]['cities'][] = $city;

                    }
                }
            }
            return $countriesArray;
        }, 60);
    }

    public function link(Geo $region = null): string
    {
        $text = $region ? $region->name : "Сбросить фильтр";
        if ($this->type == 'board') {
            return Html::a($text, BoardHelper::categoryUrl($this->category, $region));
        } else if ($this->type == 'company') {
            return Html::a($text, CompanyHelper::categoryUrl($this->category, $region));
        } else if ($this->type == 'trade') {
            return Html::a($text, TradeHelper::categoryUrl($this->category, $region));
        } else if ($this->type === null) {
            return Html::a($text, 'javascript:void(0)', [
                'data-id' => $region->id,
                'data-geo' => 'link',
            ]);
        } else if ($this->type === 'delivery') {
            return Html::checkbox('regions[' . $this->deliveryId . '][]', in_array($region->id, $this->selectedIds), [
                'label' => $text,
                'class' => 'v-checkbox',
                'value' => $region->id,
            ]);
        } else {
            throw new InvalidArgumentException("Wrong type parameter");
        }
    }

    private function getJs()
    {
        return <<<JS
    $('#modalRegion').on('click', '[data-geo=link]', function() {
      var name = $(this).text(),
          id = $(this).attr('data-id');
      $('#region-select-link').text(name);
      $('#form-geo-id').val(id).parent().removeClass('has-error').addClass('has-success').find('.help-block-error').text('');
      $('#modalRegion').modal('hide');
    });
JS;
    }
}