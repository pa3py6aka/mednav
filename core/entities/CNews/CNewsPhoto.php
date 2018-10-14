<?php

namespace core\entities\CNews;

use core\entities\PhotoInterface;
use core\entities\PhotoTrait;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%cnews_photos}}".
 *
 * @property int $id
 * @property int $cnews_id
 * @property string $file
 * @property int $sort
 *
 * @property CNews $cNews
 * @property CNews[] $cNewss
 */
class CNewsPhoto extends ActiveRecord implements PhotoInterface
{
    use PhotoTrait;

    public static function create($cnewsId, $file, $sort): CNewsPhoto
    {
        $photo = new static();
        $photo->cnews_id = $cnewsId;
        $photo->file = $file;
        $photo->sort = $sort;
        return $photo;
    }

    public static function getRelationAttribute(): string
    {
        return 'cnews_id';
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%cnews_photos}}';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cnews_id' => 'CNews ID',
            'file' => 'File',
            'sort' => 'Sort',
        ];
    }

    public function getCNews(): ActiveQuery
    {
        return $this->hasOne(CNews::class, ['id' => 'cnews_id']);
    }

    public function getCNewss(): ActiveQuery
    {
        return $this->hasMany(CNews::class, ['main_photo_id' => 'id']);
    }
}
