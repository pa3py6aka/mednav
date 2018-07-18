<?php

namespace core\entities\Trade;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use Zelenin\yii\behaviors\Slug;

/**
 * This is the model class for table "{{%trade_tags}}".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 *
 * @property TradeTagAssignment[] $tradeTagsAssignments
 * @property Trade[] $trades
 */
class TradeTag extends ActiveRecord
{
    public static function create($name): TradeTag
    {
        $tag = new static();
        $tag->name = $name;
        return $tag;
    }

    public function behaviors()
    {
        return [
            'slug' => [
                'class' => Slug::class,
                'slugAttribute' => 'slug',
                'attribute' => 'name',
                'transliterateOptions' => 'Russian-Latin/BGN; Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC;'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%trade_tags}}';
    }

    /**
     * @inheritdoc

    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
            [['name', 'slug'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['slug'], 'unique'],
        ];
    } */

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Тэг',
            'slug' => 'Slug',
        ];
    }

    public function getTradeTagsAssignments(): ActiveQuery
    {
        return $this->hasMany(TradeTagAssignment::class, ['tag_id' => 'id']);
    }

    public function getTrades(): ActiveQuery
    {
        return $this->hasMany(Trade::class, ['id' => 'trade_id'])->viaTable('{{%trade_tags_assignment}}', ['tag_id' => 'id']);
    }
}
