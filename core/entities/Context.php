<?php

namespace core\entities;


use Yii;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $html
 * @property int $enable [tinyint]
 * @property int $sort [tinyint]
 */
class Context extends ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->get('sqlite');
    }

    public static function tableName()
    {
        return 'context_blocks';
    }
}