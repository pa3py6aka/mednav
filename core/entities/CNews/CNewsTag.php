<?php

namespace core\entities\CNews;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use Zelenin\yii\behaviors\Slug;

/**
 * This is the model class for table "{{%cnews_tags}}".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 *
 * @property CNewsTagsAssignment[] $cNewsTagsAssignments
 * @property CNews[] $cNews
 */
class CNewsTag extends ActiveRecord
{
    public static function create($name): CNewsTag
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
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%cnews_tags}}';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'slug' => 'Slug',
        ];
    }

    public function getCNewsTagsAssignments(): ActiveQuery
    {
        return $this->hasMany(CNewsTagsAssignment::class, ['tag_id' => 'id']);
    }

    public function getCNews(): ActiveQuery
    {
        return $this->hasMany(CNews::class, ['id' => 'cnews_id'])->viaTable('{{%cnews_tags_assignment}}', ['tag_id' => 'id']);
    }
}
