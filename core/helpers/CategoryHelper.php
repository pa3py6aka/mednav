<?php

namespace core\helpers;


class CategoryHelper
{
    public static function categoryParentsString($category): string
    {
        $items = [];
        foreach ($category->parents as $parent) {
            if ($parent->isRoot()) {
                continue;
            }
            $items[] = $parent->name;
        }
        $items[] = $category->name;
        return implode(' / ', $items);
    }
}