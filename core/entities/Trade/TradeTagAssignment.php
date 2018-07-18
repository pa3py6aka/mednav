<?php

namespace core\entities\Trade;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%trade_tags_assignment}}".
 *
 * @property int $trade_id
 * @property int $tag_id
 *
 * @property Trade $trade
 * @property TradeTag $tag
 */
class TradeTagAssignment extends ActiveRecord
{
    public static function create($tradeId, $tagId): TradeTagAssignment
    {
        $assignment = new static();
        $assignment->trade_id = $tradeId;
        $assignment->tag_id = $tagId;
        return $assignment;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%trade_tags_assignment}}';
    }

    /**
     * @inheritdoc

    public function rules()
    {
        return [
            [['board_id', 'tag_id'], 'required'],
            [['board_id', 'tag_id'], 'integer'],
            [['board_id', 'tag_id'], 'unique', 'targetAttribute' => ['board_id', 'tag_id']],
            [['board_id'], 'exist', 'skipOnError' => true, 'targetClass' => Board::class, 'targetAttribute' => ['board_id' => 'id']],
            [['tag_id'], 'exist', 'skipOnError' => true, 'targetClass' => BoardTag::class, 'targetAttribute' => ['tag_id' => 'id']],
        ];
    } */

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'trade_id' => 'Trade ID',
            'tag_id' => 'Tag ID',
        ];
    }

    public function getTrade(): ActiveQuery
    {
        return $this->hasOne(Trade::class, ['id' => 'trade_id']);
    }

    public function getTag(): ActiveQuery
    {
        return $this->hasOne(TradeTag::class, ['id' => 'tag_id']);
    }
}
