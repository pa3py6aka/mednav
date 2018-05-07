<?php

namespace frontend\widgets;


use core\entities\Board\BoardCategory;
use core\entities\Geo;
use Yii;
use yii\base\Widget;

class RegionsModalWidget extends Widget
{
    const CACHE_KEY = 'regionsModal';

    /* @var BoardCategory|null */
    public $category;

    public function run()
    {
        return $this->render('regions-modal', [
            'category' => $this->category,
            'countries' => $this->getCategoriesArray(),
        ]);
    }

    private function getCategoriesArray()
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
}