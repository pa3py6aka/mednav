<?php

namespace core\entities\Board;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%board_tags_assignment}}".
 *
 * @property int $board_id
 * @property int $tag_id
 *
 * @property Board $board
 * @property BoardTag $tag
 */
class BoardTagAssignment extends ActiveRecord
{
    public static function create($boardId, $tagId): BoardTagAssignment
    {
        $assignment = new static();
        $assignment->board_id = $boardId;
        $assignment->tag_id = $tagId;
        return $assignment;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%board_tags_assignment}}';
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
            'board_id' => 'Board ID',
            'tag_id' => 'Tag ID',
        ];
    }

    public function getBoard(): ActiveQuery
    {
        return $this->hasOne(Board::class, ['id' => 'board_id']);
    }

    public function getTag(): ActiveQuery
    {
        return $this->hasOne(BoardTag::class, ['id' => 'tag_id']);
    }
}
