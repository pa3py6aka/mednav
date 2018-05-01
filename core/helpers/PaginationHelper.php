<?php

namespace core\helpers;


class PaginationHelper
{
    public const PAGINATION_NUMERIC = 1;
    public const PAGINATION_SCROLL = 2;

    public static function paginationTypes(): array
    {
        return [
            self::PAGINATION_NUMERIC => 'Постраничная пагинация',
            self::PAGINATION_SCROLL => 'Вечный скролл',
        ];
    }
}