<?php

namespace core\entities\Brand;

use core\entities\Article\common\ArticleCategoryQueryCommon;
use core\entities\CategoryTrait;
use paulzi\nestedsets\NestedSetsBehavior;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%brand_categories}}".
 *
 * @property int $id
 * @property string $name
 * @property string $context_name
 * @property int $enabled
 * @property int $not_show_on_main
 * @property int $children_only_parent
 * @property string $slug
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $title
 * @property string $description_top
 * @property int $description_top_on
 * @property string $description_bottom
 * @property int $description_bottom_on
 * @property string $meta_title_item
 * @property string $meta_description_item
 * @property int $pagination
 * @property int $active
 *
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 *
 * @property Brand[] $brands
 *
 * @property BrandCategory $parent
 * @property BrandCategory[] $parents
 * @property BrandCategory[] $children
 * @property BrandCategory $prev
 * @property BrandCategory $next
 * @mixin NestedSetsBehavior
 */
class BrandCategory extends \yii\db\ActiveRecord
{
    use CategoryTrait;

    public function getElementsCount(): int
    {
        $ids = array_merge($this->getDescendants()->select('id')->column(), [$this->id]);
        return Brand::find()->where(['category_id' => $ids])->count();
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%brand_categories}}';
    }

    public function getBrands(): ActiveQuery
    {
        return $this->hasMany(Brand::class, ['category_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ArticleCategoryQueryCommon the active query used by this AR class.
     */
    public static function find()
    {
        return new ArticleCategoryQueryCommon(get_called_class());
    }
}
