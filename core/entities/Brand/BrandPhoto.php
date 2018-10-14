<?php

namespace core\entities\Brand;

use core\entities\PhotoInterface;
use core\entities\PhotoTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%brand_photos}}".
 *
 * @property int $id
 * @property int $brand_id
 * @property string $file
 * @property int $sort
 *
 * @property Brand $brand
 * @property Brand[] $brands
 */
class BrandPhoto extends ActiveRecord implements PhotoInterface
{
    use PhotoTrait;

    public static function create($newsId, $file, $sort): BrandPhoto
    {
        $photo = new static();
        $photo->brand_id = $newsId;
        $photo->file = $file;
        $photo->sort = $sort;
        return $photo;
    }

    public static function getRelationAttribute(): string
    {
        return 'brand_id';
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%brand_photos}}';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brand_id' => 'Brand ID',
            'file' => 'File',
            'sort' => 'Sort',
        ];
    }

    public function getBrand(): ActiveQuery
    {
        return $this->hasOne(Brand::class, ['id' => 'brand_id']);
    }

    public function getBrands(): ActiveQuery
    {
        return $this->hasMany(Brand::class, ['main_photo_id' => 'id']);
    }
}
