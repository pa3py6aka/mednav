<?php

namespace core\entities\Trade;

use core\entities\CategoryTrait;
use core\entities\Trade\queries\TradeCategoryQuery;
use paulzi\nestedsets\NestedSetsBehavior;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%trade_categories}}".
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
 * @property TradeCategoryRegion[] $regions
 * @property Trade[] $companies
 *
 * @property TradeCategory $parent
 * @property TradeCategory[] $parents
 * @property TradeCategory[] $children
 * @property TradeCategory $prev
 * @property TradeCategory $next
 * @mixin NestedSetsBehavior
 */
class TradeCategory extends \yii\db\ActiveRecord
{
    use CategoryTrait;

    public function getElementsCount(): int
    {
        $ids = array_merge($this->getDescendants()->select('id')->column(), [$this->id]);
        return Trade::find()->where(['category_id' => $ids])->count();
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%trade_categories}}';
    }

    public function getRegions(): ActiveQuery
    {
        return $this->hasMany(TradeCategoryRegion::class, ['category_id' => 'id']);
    }

    public function getCompanies(): ActiveQuery
    {
        return $this->hasMany(Trade::class, ['category_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return TradeCategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TradeCategoryQuery(get_called_class());
    }
}
