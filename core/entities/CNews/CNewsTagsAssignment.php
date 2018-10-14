<?php

namespace core\entities\CNews;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%cnews_tags_assignment}}".
 *
 * @property int $cnews_id
 * @property int $tag_id
 *
 * @property CNews $cNews
 * @property CNewsTag $tag
 */
class CNewsTagsAssignment extends ActiveRecord
{
    public static function create($newsId, $tagId): CNewsTagsAssignment
    {
        $assignment = new static();
        $assignment->cnews_id = $newsId;
        $assignment->tag_id = $tagId;
        return $assignment;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%cnews_tags_assignment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cnews_id' => 'CNews ID',
            'tag_id' => 'Tag ID',
        ];
    }

    public function getCNews(): ActiveQuery
    {
        return $this->hasOne(CNews::class, ['id' => 'cnews_id']);
    }

    public function getTag(): ActiveQuery
    {
        return $this->hasOne(CNewsTag::class, ['id' => 'tag_id']);
    }
}
