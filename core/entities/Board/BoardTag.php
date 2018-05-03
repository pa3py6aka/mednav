<?php

namespace core\entities\Board;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use Zelenin\yii\behaviors\Slug;

/**
 * This is the model class for table "{{%board_tags}}".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 *
 * @property BoardTagAssignment[] $boardTagsAssignments
 * @property Board[] $boards
 */
class BoardTag extends ActiveRecord
{
    public static function create($name): BoardTag
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
        return '{{%board_tags}}';
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

    public function getBoardTagsAssignments(): ActiveQuery
    {
        return $this->hasMany(BoardTagAssignment::class, ['tag_id' => 'id']);
    }

    public function getBoards(): ActiveQuery
    {
        return $this->hasMany(Board::class, ['id' => 'board_id'])->viaTable('{{%board_tags_assignment}}', ['tag_id' => 'id']);
    }
}
