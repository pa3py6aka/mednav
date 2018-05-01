<?php

namespace core\components;


class Settings
{
    public const USER_EMAIL_ACTIVATION = 'userEmailActivation';
    public const USER_PREMODERATION = 'userPremoderation';

    public const GEO_SORT_BY_COUNT = 'geoSortByCount';
    public const GEO_SORT_BY_ALP = 'geoSortByAlp';

    public const BOARD_NAME = 'boardName';
    public const BOARD_NAME_UP = 'boardNameUP';
    public const BOARD_PAGE_SIZE = 'boardPageSize';
    public const BOARD_SMALL_SIZE = 'boardSmallSize';
    public const BOARD_BIG_SIZE = 'boardBigSize';
    public const BOARD_MAX_SIZE = 'boardMaxSize';
    public const BOARD_MODERATION = 'boardModeration';

    public const BOARD_META_TITLE = 'boardMetaTitle';
    public const BOARD_META_DESCRIPTION = 'boardMetaDescription';
    public const BOARD_META_KEYWORDS = 'boardMetaKeywords';
    public const BOARD_TITLE = 'boardTitle';
    public const BOARD_DESCRIPTION_TOP = 'boardDescriptionTop';
    public const BOARD_DESCRIPTION_TOP_ON = 'boardDescriptionTopOn';
    public const BOARD_DESCRIPTION_BOTTOM = 'boardDescriptionBottom';
    public const BOARD_DESCRIPTION_BOTTOM_ON = 'boardDescriptionBottomOn';

    protected $default = [
        self::USER_EMAIL_ACTIVATION => 1,
        self::USER_PREMODERATION => 0,

        self::GEO_SORT_BY_COUNT => 0,
        self::GEO_SORT_BY_ALP => 0,

        self::BOARD_NAME => 'Доска объявлений',
        self::BOARD_NAME_UP => 'Объявления',
        self::BOARD_PAGE_SIZE => 20,
        self::BOARD_SMALL_SIZE => 100,
        self::BOARD_BIG_SIZE => 250,
        self::BOARD_MAX_SIZE => 500,
        self::BOARD_MODERATION => 0,

        self::BOARD_META_TITLE => 'Медицинская доска объявлений. Продать, купить новое и б/у медицинское оборудование, инструменты, медицинскую мебель и расходных мед. материалы.',
        self::BOARD_META_DESCRIPTION => 'Разместить объявление о продаже медицинского оборудования, медтехники новой и б/у, мед. инструментов, медицинской мебели и материалов. Медицинская доска объявлений MedNav.ru ',
        self::BOARD_META_KEYWORDS => 'медицина, объявления, купить, продать, медтехника, медицинское оборудование',
        self::BOARD_TITLE => 'Медицинская доска объявлений',
        self::BOARD_DESCRIPTION_TOP => '',
        self::BOARD_DESCRIPTION_TOP_ON => 1,
        self::BOARD_DESCRIPTION_BOTTOM => '',
        self::BOARD_DESCRIPTION_BOTTOM_ON => 1,
    ];
}