<?php

namespace core\entities;

/**
 * Interface CategoryAssignmentInterface
 *
 * @property CategoryInterface $category
 * @property int $category_id
 */
interface CategoryAssignmentInterface
{
    public function getCategory();
}