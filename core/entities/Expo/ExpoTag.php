<?php

namespace core\entities\Expo;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use Zelenin\yii\behaviors\Slug;

/**
 * This is the model class for table "{{%exposition_tags}}".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 *
 * @property BrandTagsAssignment[] $brandTagsAssignments
 * @property Brand[] $brands
 */
class ExpoTag extends ActiveRecord
{
    public static function create($name): ExpoTag
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
        return '{{%exposition_tags}}';
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

    public function getBrandTagsAssignments(): ActiveQuery
    {
        return $this->hasMany(ExpoTagsAssignment::class, ['tag_id' => 'id']);
    }

    public function getBrands(): ActiveQuery
    {
        return $this->hasMany(expo::class, ['id' => 'exposition_id'])->viaTable('{{%exposition_tags_assignment}}', ['tag_id' => 'id']);
    }
}
