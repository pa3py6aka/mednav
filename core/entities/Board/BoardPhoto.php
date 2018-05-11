<?php

namespace core\entities\Board;

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
class BoardPhoto extends ActiveRecord
{
    public static function create($boardId, $file, $sort): BoardPhoto
    {
        $photo = new static();
        $photo->board_id = $boardId;
        $photo->file = $file;
        $photo->sort = $sort;
        return $photo;
    }

    public function getUrl($type = 'small', $absolute = false): string
    {
        return ($absolute ? Yii::$app->params['frontendHostInfo'] . '/' : '/')
            . str_replace('/max/', '/' . $type . '/', $this->file);
    }

    public function getPhotos(): array
    {
        return [
            'small' => str_replace('/max/', '/small/', $this->file),
            'big' => str_replace('/max/', '/big/', $this->file),
            'max' => $this->file,
        ];
    }

    public function removePhotos()
    {
        $path = Yii::getAlias('@frontend/web/');
        foreach ($this->getPhotos() as $file) {
            if (is_file($path . $file)) {
                FileHelper::unlink($path . $file);
            }
        }
    }

    public function afterDelete()
    {
        $this->removePhotos();
        return parent::afterDelete();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%board_photos}}';
    }

    /**
     * @inheritdoc

    public function rules()
    {
        return [
            [['board_id', 'file'], 'required'],
            [['board_id'], 'integer'],
            [['file'], 'string', 'max' => 255],
            [['sort'], 'string', 'max' => 3],
            [['file'], 'unique'],
            [['board_id'], 'exist', 'skipOnError' => false, 'targetClass' => Board::class, 'targetAttribute' => ['board_id' => 'id']],
        ];
    }*/

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'board_id' => 'Board ID',
            'file' => 'File',
            'sort' => 'Sort',
        ];
    }

    public function getBoard(): ActiveQuery
    {
        return $this->hasOne(Board::class, ['id' => 'board_id']);
    }
}
