<?php

namespace core\components;


class Settings
{
    public const USER_EMAIL_ACTIVATION = 'userEmailActivation';
    public const USER_PREMODERATION = 'userPremoderation';

    public const GEO_SORT_BY_COUNT = 'geoSortByCount';
    public const GEO_SORT_BY_ALP = 'geoSortByAlp';

    protected $default = [
        self::USER_EMAIL_ACTIVATION => 1,
        self::USER_PREMODERATION => 0,

        self::GEO_SORT_BY_COUNT => 0,
        self::GEO_SORT_BY_ALP => 0,
    ];
}