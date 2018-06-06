<?php

namespace core\entities\Company;

use core\entities\CategoryRegionTrait;
use core\entities\Geo;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%company_category_regions}}".
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
 * @property CompanyCategory $category
 * @property Geo $geo
 */
class CompanyCategoryRegion extends ActiveRecord
{
    use CategoryRegionTrait;

    public static function tableName(): string
    {
        return '{{%company_category_regions}}';
    }

    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(CompanyCategory::class, ['id' => 'category_id']);
    }

    public function getGeo(): ActiveQuery
    {
        return $this->hasOne(Geo::class, ['id' => 'geo_id']);
    }
}
