<?php

namespace core\entities\Brand;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%brand_tags_assignment}}".
 *
 * @property int $brand_id
 * @property int $tag_id
 *
 * @property Brand $brand
 * @property BrandTag $tag
 */
class BrandTagsAssignment extends ActiveRecord
{
    public static function create($brandId, $tagId): BrandTagsAssignment
    {
        $assignment = new static();
        $assignment->brand_id = $brandId;
        $assignment->tag_id = $tagId;
        return $assignment;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%brand_tags_assignment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'brand_id' => 'Brand ID',
            'tag_id' => 'Tag ID',
        ];
    }

    public function getBrand(): ActiveQuery
    {
        return $this->hasOne(Brand::class, ['id' => 'brand_id']);
    }

    public function getTag(): ActiveQuery
    {
        return $this->hasOne(BrandTag::class, ['id' => 'tag_id']);
    }
}
