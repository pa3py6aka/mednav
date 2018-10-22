<?php

namespace core\entities\Expo;

use core\entities\PhotoInterface;
use core\entities\PhotoTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%exposition_photos}}".
 *
 * @property int $id
 * @property int $exposition_id
 * @property string $file
 * @property int $sort
 *
 * @property Expo $expo
 * @property Expo[] $expos
 */
class ExpoPhoto extends ActiveRecord implements PhotoInterface
{
    use PhotoTrait;

    public static function create($expoId, $file, $sort): ExpoPhoto
    {
        $photo = new static();
        $photo->exposition_id = $expoId;
        $photo->file = $file;
        $photo->sort = $sort;
        return $photo;
    }

    public static function getRelationAttribute(): string
    {
        return 'exposition_id';
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%exposition_photos}}';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'exposition_id' => 'Expo ID',
            'file' => 'File',
            'sort' => 'Sort',
        ];
    }

    public function getExpo(): ActiveQuery
    {
        return $this->hasOne(Expo::class, ['id' => 'exposition_id']);
    }

    public function getExpos(): ActiveQuery
    {
        return $this->hasMany(Expo::class, ['main_photo_id' => 'id']);
    }
}
