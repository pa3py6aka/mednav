<?php

namespace core\entities\Expo;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%exposition_tags_assignment}}".
 *
 * @property int $exposition_id
 * @property int $tag_id
 *
 * @property Expo $expo
 * @property ExpoTag $tag
 */
class ExpoTagsAssignment extends ActiveRecord
{
    public static function create($expoId, $tagId): ExpoTagsAssignment
    {
        $assignment = new static();
        $assignment->exposition_id = $expoId;
        $assignment->tag_id = $tagId;
        return $assignment;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%exposition_tags_assignment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'exposition_id' => 'Expo ID',
            'tag_id' => 'Tag ID',
        ];
    }

    public function getExpo(): ActiveQuery
    {
        return $this->hasOne(Expo::class, ['id' => 'exposition_id']);
    }

    public function getTag(): ActiveQuery
    {
        return $this->hasOne(ExpoTag::class, ['id' => 'tag_id']);
    }
}
