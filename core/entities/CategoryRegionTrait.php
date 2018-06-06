<?php

namespace core\entities;

/**
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
 */
trait CategoryRegionTrait
{
    public static function fastCreate($categoryId, $regionId): self
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
}