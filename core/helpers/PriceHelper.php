<?php

namespace core\helpers;


class PriceHelper
{
    public const REGEXP = '/^[0-9]+((\.|,)[0-9]{2})?$/uis';

    public static function optimize($price)
    {
        $price = (float) str_replace(',', '.', $price);
        return $price * 100;
    }

    public static function normalize($price)
    {
        $price = number_format($price/100, 2, ',', ' ');
        return str_replace(',00', '', $price);
    }
}