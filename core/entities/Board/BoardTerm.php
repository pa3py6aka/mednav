<?php

namespace core\entities\Board;


use yii2tech\filedb\ActiveRecord;

/**
 * @property int $id
 * @property int $days
 * @property string $daysHuman
 * @property int $default
 * @property int $notification
 */
class BoardTerm extends ActiveRecord
{
    public static function fileName()
    {
        return 'board-terms';
    }

    public function attributes()
    {
        return ['id', 'days', 'daysHuman', 'default', 'notification'];
    }

    public function getNotificationInSeconds(): int
    {
        return $this->notification * 24 * 3600;
    }

    public static function getDefaultId(): int
    {
        return self::find()->where(['default' => 1])->one()->id;
    }
}