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

    public const COMPANY_NAME = 'companyName';
    public const COMPANY_NAME_UP = 'companyNameUP';
    public const COMPANY_PAGE_SIZE = 'companyPageSize';
    public const COMPANY_SMALL_SIZE = 'companySmallSize';
    public const COMPANY_BIG_SIZE = 'companyBigSize';
    public const COMPANY_MAX_SIZE = 'companyMaxSize';
    public const COMPANY_MODERATION = 'companyModeration';

    public const COMPANY_META_TITLE = 'companyMetaTitle';
    public const COMPANY_META_DESCRIPTION = 'companyMetaDescription';
    public const COMPANY_META_KEYWORDS = 'companyMetaKeywords';
    public const COMPANY_TITLE = 'companyTitle';
    public const COMPANY_DESCRIPTION_TOP = 'companyDescriptionTop';
    public const COMPANY_DESCRIPTION_TOP_ON = 'companyDescriptionTopOn';
    public const COMPANY_DESCRIPTION_BOTTOM = 'companyDescriptionBottom';
    public const COMPANY_DESCRIPTION_BOTTOM_ON = 'companyDescriptionBottomOn';

    public const TRADE_NAME = 'tradeName';
    public const TRADE_NAME_UP = 'tradeNameUP';
    public const TRADE_PAGE_SIZE = 'tradePageSize';
    public const TRADE_SMALL_SIZE = 'tradeSmallSize';
    public const TRADE_BIG_SIZE = 'tradeBigSize';
    public const TRADE_MAX_SIZE = 'tradeMaxSize';
    public const TRADE_MODERATION = 'tradeModeration';

    public const TRADE_META_TITLE = 'tradeMetaTitle';
    public const TRADE_META_DESCRIPTION = 'tradeMetaDescription';
    public const TRADE_META_KEYWORDS = 'tradeMetaKeywords';
    public const TRADE_TITLE = 'tradeTitle';
    public const TRADE_DESCRIPTION_TOP = 'tradeDescriptionTop';
    public const TRADE_DESCRIPTION_TOP_ON = 'tradeDescriptionTopOn';
    public const TRADE_DESCRIPTION_BOTTOM = 'tradeDescriptionBottom';
    public const TRADE_DESCRIPTION_BOTTOM_ON = 'tradeDescriptionBottomOn';

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

        self::COMPANY_NAME => 'Каталог компаний',
        self::COMPANY_NAME_UP => 'Моя компания',
        self::COMPANY_PAGE_SIZE => 15,
        self::COMPANY_SMALL_SIZE => 100,
        self::COMPANY_BIG_SIZE => 250,
        self::COMPANY_MAX_SIZE => 500,
        self::COMPANY_MODERATION => 0,

        self::COMPANY_META_TITLE => 'Каталог поставщиков и производителей медтехники и медицинского оборудования.',
        self::COMPANY_META_DESCRIPTION => 'Все поставщики и производители медицинской техники, оборудования и расходных материалов.',
        self::COMPANY_META_KEYWORDS => 'медтехника, медицинское оборудование, поставщики медтехники, производители мед. оборудования',
        self::COMPANY_TITLE => 'Поставщики и производители медоборудования и материалов',
        self::COMPANY_DESCRIPTION_TOP => '',
        self::COMPANY_DESCRIPTION_TOP_ON => 1,
        self::COMPANY_DESCRIPTION_BOTTOM => '',
        self::COMPANY_DESCRIPTION_BOTTOM_ON => 1,

        self::TRADE_NAME => 'Каталог товаров',
        self::TRADE_NAME_UP => 'Товары',
        self::TRADE_PAGE_SIZE => 19,
        self::TRADE_SMALL_SIZE => 100,
        self::TRADE_BIG_SIZE => 250,
        self::TRADE_MAX_SIZE => 500,
        self::TRADE_MODERATION => 0,

        self::TRADE_META_TITLE => 'Медицинское оборудование, мебель, инструменты и расходные материалы',
        self::TRADE_META_DESCRIPTION => 'Производители и поставщики медицинского оборудования, мед. техники, инструментов, мебели для мед. учреждений. MedNav.ru',
        self::TRADE_META_KEYWORDS => 'медицинская техника, мед. оборудование, инструменты, медициские расходные материалы',
        self::TRADE_TITLE => 'Медицинское оборудование, инструменты и материалы',
        self::TRADE_DESCRIPTION_TOP => '',
        self::TRADE_DESCRIPTION_TOP_ON => 1,
        self::TRADE_DESCRIPTION_BOTTOM => '',
        self::TRADE_DESCRIPTION_BOTTOM_ON => 1,
    ];
}