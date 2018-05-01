<?php

namespace core\entities\Board;

use core\entities\Geo;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%board_category_regions}}".
 *
 * @property int $category_id
 * @property int $geo_id
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $title
 * @property string $description_top
 * @property int $description_top_on
 * @property string $description_bottom
 * @property int $description_bottom_on
 *
 * @property BoardCategory $category
 * @property Geo $geo
 */
class BoardCategoryRegion extends ActiveRecord
{
    public static function fastCreate($categoryId, $regionId): BoardCategoryRegion
    {
        $assignment = new static();
        $assignment->category_id = $categoryId;
        $assignment->geo_id = $regionId;
        $assignment->meta_description = '';
        $assignment->meta_keywords = '';
        $assignment->description_top = '';
        $assignment->description_bottom = '';
        return $assignment;
    }

    public function edit(
        $metaTitle,
        $metaDescription,
        $metaKeywords,
        $title,
        $descriptionTop,
        $descriptionTopOn,
        $descriptionBottom,
        $descriptionBottomOn
    ): void
    {
        $this->meta_title = $metaTitle;
        $this->meta_description = $metaDescription;
        $this->meta_keywords = $metaKeywords;
        $this->title = $title;
        $this->description_top = $descriptionTop;
        $this->description_top_on = $descriptionTopOn;
        $this->description_bottom = $descriptionBottom;
        $this->description_bottom_on = $descriptionBottomOn;
    }

    public static function tableName(): string
    {
        return '{{%board_category_regions}}';
    }

    /**
     * @inheritdoc

    public function rules()
    {
        return [
            [['category_id', 'geo_id', 'meta_description', 'meta_keywords', 'description_top', 'description_bottom'], 'required'],
            [['category_id', 'geo_id'], 'integer'],
            [['meta_description', 'meta_keywords', 'description_top', 'description_bottom'], 'string'],
            [['meta_title', 'title'], 'string', 'max' => 255],
            [['description_top_on', 'description_bottom_on'], 'string', 'max' => 1],
            [['category_id', 'geo_id'], 'unique', 'targetAttribute' => ['category_id', 'geo_id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => BoardCategory::class, 'targetAttribute' => ['category_id' => 'id']],
            [['geo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Geo::class, 'targetAttribute' => ['geo_id' => 'id']],
        ];
    } */

    public function attributeLabels(): array
    {
        return [
            'category_id' => 'Category ID',
            'geo_id' => 'Geo ID',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'title' => 'Title',
            'description_top' => 'Description Top',
            'description_top_on' => 'Description Top On',
            'description_bottom' => 'Description Bottom',
            'description_bottom_on' => 'Description Bottom On',
        ];
    }

    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(BoardCategory::class, ['id' => 'category_id']);
    }

    public function getGeo(): ActiveQuery
    {
        return $this->hasOne(Geo::class, ['id' => 'geo_id']);
    }
}
