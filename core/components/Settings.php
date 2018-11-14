<?php

namespace core\components;


class Settings
{
    public const GENERAL_TITLE = 'generalTitle';
    public const GENERAL_DESCRIPTION = 'generalDescription';
    public const GENERAL_KEYWORDS = 'generalKeywords';
    public const GENERAL_EMAIL = 'generalEmail';
    public const GENERAL_EMAIL_FROM = 'generalEmailFrom';
    public const GENERAL_MODALS_SHOWTIME = 'generalModalsShowtime';
    public const GENERAL_CONTACT_EMAIL = 'generalContactEmail';

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

    public const ARTICLE_NAME = 'articleName';
    public const ARTICLE_NAME_UP = 'articleNameUP';
    public const ARTICLE_PAGE_SIZE = 'articlePageSize';
    public const ARTICLE_SMALL_SIZE = 'articleSmallSize';
    public const ARTICLE_BIG_SIZE = 'articleBigSize';
    public const ARTICLE_MAX_SIZE = 'articleMaxSize';
    public const ARTICLE_MODERATION = 'articleModeration';

    public const ARTICLE_META_TITLE = 'articleMetaTitle';
    public const ARTICLE_META_DESCRIPTION = 'articleMetaDescription';
    public const ARTICLE_META_KEYWORDS = 'articleMetaKeywords';
    public const ARTICLE_TITLE = 'articleTitle';
    public const ARTICLE_DESCRIPTION_TOP = 'articleDescriptionTop';
    public const ARTICLE_DESCRIPTION_TOP_ON = 'articleDescriptionTopOn';
    public const ARTICLE_DESCRIPTION_BOTTOM = 'articleDescriptionBottom';
    public const ARTICLE_DESCRIPTION_BOTTOM_ON = 'articleDescriptionBottomOn';

    public const NEWS_NAME = 'newsName';
    public const NEWS_NAME_UP = 'newsNameUP';
    public const NEWS_PAGE_SIZE = 'newsPageSize';
    public const NEWS_SMALL_SIZE = 'newsSmallSize';
    public const NEWS_BIG_SIZE = 'newsBigSize';
    public const NEWS_MAX_SIZE = 'newsMaxSize';
    public const NEWS_MODERATION = 'newsModeration';

    public const NEWS_META_TITLE = 'newsMetaTitle';
    public const NEWS_META_DESCRIPTION = 'newsMetaDescription';
    public const NEWS_META_KEYWORDS = 'newsMetaKeywords';
    public const NEWS_TITLE = 'newsTitle';
    public const NEWS_DESCRIPTION_TOP = 'newsDescriptionTop';
    public const NEWS_DESCRIPTION_TOP_ON = 'newsDescriptionTopOn';
    public const NEWS_DESCRIPTION_BOTTOM = 'newsDescriptionBottom';
    public const NEWS_DESCRIPTION_BOTTOM_ON = 'newsDescriptionBottomOn';

    public const CNEWS_NAME = 'cnewsName';
    public const CNEWS_NAME_UP = 'cnewsNameUP';
    public const CNEWS_PAGE_SIZE = 'cnewsPageSize';
    public const CNEWS_SMALL_SIZE = 'cnewsSmallSize';
    public const CNEWS_BIG_SIZE = 'cnewsBigSize';
    public const CNEWS_MAX_SIZE = 'cnewsMaxSize';
    public const CNEWS_MODERATION = 'cnewsModeration';

    public const CNEWS_META_TITLE = 'cnewsMetaTitle';
    public const CNEWS_META_DESCRIPTION = 'cnewsMetaDescription';
    public const CNEWS_META_KEYWORDS = 'cnewsMetaKeywords';
    public const CNEWS_TITLE = 'cnewsTitle';
    public const CNEWS_DESCRIPTION_TOP = 'cnewsDescriptionTop';
    public const CNEWS_DESCRIPTION_TOP_ON = 'cnewsDescriptionTopOn';
    public const CNEWS_DESCRIPTION_BOTTOM = 'cnewsDescriptionBottom';
    public const CNEWS_DESCRIPTION_BOTTOM_ON = 'cnewsDescriptionBottomOn';

    public const BRANDS_NAME = 'brandsName';
    public const BRANDS_NAME_UP = 'brandsNameUP';
    public const BRANDS_PAGE_SIZE = 'brandsPageSize';
    public const BRANDS_SMALL_SIZE = 'brandsSmallSize';
    public const BRANDS_BIG_SIZE = 'brandsBigSize';
    public const BRANDS_MAX_SIZE = 'brandsMaxSize';
    public const BRANDS_MODERATION = 'brandsModeration';

    public const BRANDS_META_TITLE = 'brandsMetaTitle';
    public const BRANDS_META_DESCRIPTION = 'brandsMetaDescription';
    public const BRANDS_META_KEYWORDS = 'brandsMetaKeywords';
    public const BRANDS_TITLE = 'brandsTitle';
    public const BRANDS_DESCRIPTION_TOP = 'brandsDescriptionTop';
    public const BRANDS_DESCRIPTION_TOP_ON = 'brandsDescriptionTopOn';
    public const BRANDS_DESCRIPTION_BOTTOM = 'brandsDescriptionBottom';
    public const BRANDS_DESCRIPTION_BOTTOM_ON = 'brandsDescriptionBottomOn';

    public const EXPO_NAME = 'expoName';
    public const EXPO_NAME_UP = 'expoNameUP';
    public const EXPO_PAGE_SIZE = 'expoPageSize';
    public const EXPO_SMALL_SIZE = 'expoSmallSize';
    public const EXPO_BIG_SIZE = 'expoBigSize';
    public const EXPO_MAX_SIZE = 'expoMaxSize';
    public const EXPO_MODERATION = 'expoModeration';

    public const EXPO_META_TITLE = 'expoMetaTitle';
    public const EXPO_META_DESCRIPTION = 'expoMetaDescription';
    public const EXPO_META_KEYWORDS = 'expoMetaKeywords';
    public const EXPO_TITLE = 'expoTitle';
    public const EXPO_DESCRIPTION_TOP = 'expoDescriptionTop';
    public const EXPO_DESCRIPTION_TOP_ON = 'expoDescriptionTopOn';
    public const EXPO_DESCRIPTION_BOTTOM = 'expoDescriptionBottom';
    public const EXPO_DESCRIPTION_BOTTOM_ON = 'expoDescriptionBottomOn';

    protected $default = [
        self::GENERAL_TITLE => 'Поставщики и производители медицинского оборудования в России',
        self::GENERAL_DESCRIPTION => 'Продажа медтехники и медицинского оборудования, расходных материалов и инструментов. Каталог поставщиков, дистрибьюторов и производителей медицинского оборудования и материалов для мед. учреждений, больниц и клиник России',
        self::GENERAL_KEYWORDS => 'медтехника, медицинское оборудование, медицинские материалы, продажа, покупка, сервис, ремонт медтехники',
        self::GENERAL_EMAIL => 'no-reply@mednav.ru',
        self::GENERAL_EMAIL_FROM => 'MedNav.ru',
        self::GENERAL_MODALS_SHOWTIME => 4,
        self::GENERAL_CONTACT_EMAIL => 'contact@mednav.ru',

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

        self::ARTICLE_NAME => 'Статьи',
        self::ARTICLE_NAME_UP => 'Мои статьи',
        self::ARTICLE_PAGE_SIZE => 10,
        self::ARTICLE_SMALL_SIZE => 100,
        self::ARTICLE_BIG_SIZE => 250,
        self::ARTICLE_MAX_SIZE => 1000,
        self::ARTICLE_MODERATION => 1,

        self::ARTICLE_META_TITLE => 'Справочные материалы и публикации по медицинскому оборудованию',
        self::ARTICLE_META_DESCRIPTION => 'Материалы по медицинской техники, расходным материалам',
        self::ARTICLE_META_KEYWORDS => '',
        self::ARTICLE_TITLE => '',
        self::ARTICLE_DESCRIPTION_TOP => '',
        self::ARTICLE_DESCRIPTION_TOP_ON => 1,
        self::ARTICLE_DESCRIPTION_BOTTOM => '',
        self::ARTICLE_DESCRIPTION_BOTTOM_ON => 1,

        self::NEWS_NAME => 'Новости',
        self::NEWS_NAME_UP => 'Мои новости',
        self::NEWS_PAGE_SIZE => 10,
        self::NEWS_SMALL_SIZE => 100,
        self::NEWS_BIG_SIZE => 250,
        self::NEWS_MAX_SIZE => 1000,
        self::NEWS_MODERATION => 1,

        self::NEWS_META_TITLE => 'Новости',
        self::NEWS_META_DESCRIPTION => 'Новости по медицинской технике, расходным материалам',
        self::NEWS_META_KEYWORDS => '',
        self::NEWS_TITLE => '',
        self::NEWS_DESCRIPTION_TOP => '',
        self::NEWS_DESCRIPTION_TOP_ON => 1,
        self::NEWS_DESCRIPTION_BOTTOM => '',
        self::NEWS_DESCRIPTION_BOTTOM_ON => 1,

        self::CNEWS_NAME => 'Новости компаний',
        self::CNEWS_NAME_UP => 'Мои новости',
        self::CNEWS_PAGE_SIZE => 10,
        self::CNEWS_SMALL_SIZE => 100,
        self::CNEWS_BIG_SIZE => 250,
        self::CNEWS_MAX_SIZE => 1000,
        self::CNEWS_MODERATION => 1,

        self::CNEWS_META_TITLE => 'Новости компаний',
        self::CNEWS_META_DESCRIPTION => 'Новости компаний по медицинской технике, расходным материалам',
        self::CNEWS_META_KEYWORDS => '',
        self::CNEWS_TITLE => '',
        self::CNEWS_DESCRIPTION_TOP => '',
        self::CNEWS_DESCRIPTION_TOP_ON => 1,
        self::CNEWS_DESCRIPTION_BOTTOM => '',
        self::CNEWS_DESCRIPTION_BOTTOM_ON => 1,

        self::BRANDS_NAME => 'Бренды',
        self::BRANDS_NAME_UP => 'Мои бренды',
        self::BRANDS_PAGE_SIZE => 10,
        self::BRANDS_SMALL_SIZE => 100,
        self::BRANDS_BIG_SIZE => 250,
        self::BRANDS_MAX_SIZE => 1000,
        self::BRANDS_MODERATION => 1,

        self::BRANDS_META_TITLE => 'Бренды',
        self::BRANDS_META_DESCRIPTION => 'Бренды медицинских компаний',
        self::BRANDS_META_KEYWORDS => '',
        self::BRANDS_TITLE => '',
        self::BRANDS_DESCRIPTION_TOP => '',
        self::BRANDS_DESCRIPTION_TOP_ON => 1,
        self::BRANDS_DESCRIPTION_BOTTOM => '',
        self::BRANDS_DESCRIPTION_BOTTOM_ON => 1,

        self::EXPO_NAME => 'Выставки',
        self::EXPO_NAME_UP => 'Мои выставки',
        self::EXPO_PAGE_SIZE => 10,
        self::EXPO_SMALL_SIZE => 100,
        self::EXPO_BIG_SIZE => 250,
        self::EXPO_MAX_SIZE => 1000,
        self::EXPO_MODERATION => 1,

        self::EXPO_META_TITLE => 'Выставки',
        self::EXPO_META_DESCRIPTION => 'Выставки медицинских компаний',
        self::EXPO_META_KEYWORDS => '',
        self::EXPO_TITLE => '',
        self::EXPO_DESCRIPTION_TOP => '',
        self::EXPO_DESCRIPTION_TOP_ON => 1,
        self::EXPO_DESCRIPTION_BOTTOM => '',
        self::EXPO_DESCRIPTION_BOTTOM_ON => 1,
    ];
}