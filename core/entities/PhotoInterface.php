<?php

namespace core\entities;


use yii\db\ActiveQuery;

interface PhotoInterface
{
    /**
     * @param int $boardId
     * @param string $file
     * @param int $sort
     * @return \core\entities\Board\BoardPhoto|\core\entities\Company\CompanyPhoto
     */
    public static function create($boardId, $file, $sort);

    public static function getRelationAttribute(): string;

    /**
     * @return ActiveQuery
     */
    public static function find();
}