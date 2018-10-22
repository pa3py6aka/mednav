<?php

namespace core\entities;
use core\entities\Article\common\ArticleCategoryQueryCommon;
use core\entities\Board\queries\BoardCategoryQuery;

/**
 * Interface CategoryInterface
 *
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
 * @property CategoryInterface $parent
 * @property CategoryInterface[] $parents
 * @property CategoryInterface[] $children
 * @property CategoryInterface $prev
 * @property CategoryInterface $next
 *
 * @method ArticleCategoryQueryCommon|BoardCategoryQuery find
 */
interface CategoryInterface
{
    public function getElementsCount(): int;
}