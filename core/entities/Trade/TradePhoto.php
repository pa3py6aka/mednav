<?php

namespace core\entities\Trade;

use core\entities\PhotoInterface;
use core\entities\PhotoTrait;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%trade_photos}}".
 *
 * @property int $id
 * @property int $trade_id
 * @property string $file
 * @property int $sort
 *
 * @property Trade $trade
 */
class TradePhoto extends ActiveRecord implements PhotoInterface
{
    use PhotoTrait;

    public static function create($tradeId, $file, $sort): TradePhoto
    {
        $photo = new static();
        $photo->trade_id = $tradeId;
        $photo->file = $file;
        $photo->sort = $sort;
        return $photo;
    }

    public static function getRelationAttribute(): string
    {
        return 'trade_id';
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%trade_photos}}';
    }

    public function getTrade(): ActiveQuery
    {
        return $this->hasOne(Trade::class, ['id' => 'trade_id']);
    }
}
