<?php

namespace core\entities\Company;

use core\entities\CategoryInterface;
use core\entities\CategoryTrait;
use core\entities\Company\queries\CompanyCategoryQuery;
use paulzi\nestedsets\NestedSetsBehavior;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%company_categories}}".
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
 * @property string $meta_title_other [varchar(255)]
 * @property string $meta_description_other
 * @property string $meta_keywords_other
 * @property string $title_other [varchar(255)]
 * @property int $pagination
 * @property int $active
 *
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 *
 * @property CompanyCategoryRegion[] $regions
 * @property Company[] $companies
 *
 * @property CompanyCategory $parent
 * @property CompanyCategory[] $parents
 * @property CompanyCategory[] $children
 * @property CompanyCategory $prev
 * @property CompanyCategory $next
 * @mixin NestedSetsBehavior
 */
class CompanyCategory extends \yii\db\ActiveRecord implements CategoryInterface
{
    use CategoryTrait;

    public function getElementsCount(): int
    {
        $ids = array_merge($this->getDescendants()->select('id')->column(), [$this->id]);
        return Company::find()
            ->alias('c')
            ->leftJoin(CompanyCategoryAssignment::tableName() . " cca", "c.id=cca.company_id")
            ->where(['cca.category_id' => $ids])
            ->count();
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%company_categories}}';
    }

    public function getRegions(): ActiveQuery
    {
        return $this->hasMany(CompanyCategoryRegion::class, ['category_id' => 'id']);
    }

    public function getCompanies(): ActiveQuery
    {
        return $this->hasMany(Company::class, ['category_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return CompanyCategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CompanyCategoryQuery(get_called_class());
    }
}
