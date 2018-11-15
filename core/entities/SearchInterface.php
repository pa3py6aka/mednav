<?php

namespace core\entities;


use yii\db\ActiveQuery;

interface SearchInterface
{
    public static function getSearchQuery($text): ActiveQuery;
}