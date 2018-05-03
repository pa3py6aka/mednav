<?php

namespace core\entities;


use yii2tech\filedb\ActiveRecord;

/**
 * @property int $id
 * @property int $name
 * @property string $sign
 * @property int $default
 */
class Currency extends ActiveRecord
{
    public static function fileName()
    {
        return 'currencies';
    }

    public function attributes()
    {
        return ['id', 'name', 'sign', 'default'];
    }
}