<?php

namespace core\helpers;


use core\useCases\SearchService;
use Yii;
use yii\helpers\Html;

class SearchHelper
{
    public static function componentsDropdown(): string
    {
        return Html::dropDownList('for', Yii::$app->request->get('for'), SearchService::componentsArray(),['class' => 'form-control']);
    }
}