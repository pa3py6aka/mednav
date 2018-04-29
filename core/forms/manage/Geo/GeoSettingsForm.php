<?php

namespace core\forms\manage\Geo;


use core\components\SettingsManager;
use core\forms\manage\CommonSettingsForm;

class GeoSettingsForm extends CommonSettingsForm
{
    public $geoSortByCount;
    public $geoSortByAlp;

    public function rules()
    {
        return [
            [[SettingsManager::GEO_SORT_BY_COUNT, SettingsManager::GEO_SORT_BY_ALP], 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            SettingsManager::GEO_SORT_BY_COUNT => 'Сортировать по кол-ву контента',
            SettingsManager::GEO_SORT_BY_ALP => 'Сортировать в алфавитном порядке',
        ];
    }

}