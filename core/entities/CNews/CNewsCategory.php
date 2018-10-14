<?php

namespace core\entities\CNews;

use core\entities\Article\common\ArticleCategoryQueryCommon;
use core\entities\CategoryTrait;
use paulzi\nestedsets\NestedSetsBehavior;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%cnews_categories}}".
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
 * @property CNews[] $cNews
 *
 * @property CNewsCategory $parent
 * @property CNewsCategory[] $parents
 * @property CNewsCategory[] $children
 * @property CNewsCategory $prev
 * @property CNewsCategory $next
 * @mixin NestedSetsBehavior
 */
class CNewsCategory extends \yii\db\ActiveRecord
{
    use CategoryTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%cnews_categories}}';
    }

    public function getCNews(): ActiveQuery
    {
        return $this->hasMany(CNews::class, ['category_id' => 'id']);
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
