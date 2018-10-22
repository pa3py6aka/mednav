<?php

namespace core\entities\Brand;

use core\entities\Article\common\ArticleCategoryQueryCommon;
use core\entities\CategoryInterface;
use core\entities\CategoryTrait;
use paulzi\nestedsets\NestedSetsBehavior;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%brand_categories}}".
 *
 * @property int $id
 *
 * @property Brand[] $brands
 *
 * @property BrandCategory $parent
 * @property BrandCategory[] $parents
 * @property BrandCategory[] $children
 * @property BrandCategory $prev
 * @property BrandCategory $next
 * @mixin NestedSetsBehavior
 * @method bool isRoot
 */
class BrandCategory extends \yii\db\ActiveRecord implements CategoryInterface
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
