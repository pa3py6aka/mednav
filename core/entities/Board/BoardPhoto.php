<?php

namespace core\entities\Board;

use core\entities\PhotoInterface;
use core\entities\PhotoTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "{{%board_photos}}".
 *
 * @property int $id
 * @property int $board_id
 * @property string $file
 * @property int $sort
 *
 * @property Board $board
 */
class BoardPhoto extends ActiveRecord implements PhotoInterface
{
    use PhotoTrait;

    public static function create($boardId, $file, $sort): BoardPhoto
    {
        $photo = new static();
        $photo->board_id = $boardId;
        $photo->file = $file;
        $photo->sort = $sort;
        return $photo;
    }

    public static function getRelationAttribute(): string
    {
        return 'board_id';
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%board_photos}}';
    }

    public function getBoard(): ActiveQuery
    {
        return $this->hasOne(Board::class, ['id' => 'board_id']);
    }
}
