<?php

namespace core\entities;


use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * @property int $id
 * @property bool $type [tinyint]
 * @property string $name [varchar(255)]
 * @property bool $enable [tinyint]
 * @property bool $show_title [tinyint]
 * @property bool $view [tinyint]
 * @property bool $items [tinyint]
 * @property bool $for_module [tinyint]
 * @property string|array $html
 * @property bool $module [tinyint]
 * @property string|array $htmlCategories
 * @property bool $place [tinyint]
 * @property bool $page [tinyint]
 * @property bool $sort [tinyint]
 */
class ContentBlock extends ActiveRecord
{
    public const TYPE_NEW = 1;
    public const TYPE_POPULAR = 2;
    public const TYPE_SIMILAR = 3;
    public const TYPE_HTML = 4;

    public const VIEW_CAROUSEL = 1;
    public const VIEW_TILE = 2;
    public const VIEW_LINE = 3;

    public const MODULE_MAIN_PAGE = 1;
    public const MODULE_BOARD = 2;
    public const MODULE_TRADE = 3;
    public const MODULE_COMPANY = 4;
    public const MODULE_ARTICLE = 5;
    public const MODULE_BRAND = 6;
    public const MODULE_CNEWS = 7;
    public const MODULE_EXPO = 8;
    public const MODULE_NEWS = 9;

    public const PLACE_SIDEBAR_LEFT = 1;
    public const PLACE_SIDEBAR_RIGHT = 2;
    public const PLACE_MAIN = 3;

    public const PAGE_LISTING = 1;
    public const PAGE_VIEW = 2;

    public static function modulesArray($withMainPage = true): array
    {
        $array = [
            self::MODULE_MAIN_PAGE => 'Главная страница',
            self::MODULE_BOARD => 'Доска объявлений',
            self::MODULE_TRADE => 'Каталог товаров',
            self::MODULE_COMPANY => 'Каталог компаний',
            self::MODULE_ARTICLE => 'Статьи',
            self::MODULE_BRAND => 'Бренды',
            self::MODULE_CNEWS => 'Новости компаний',
            self::MODULE_EXPO => 'Выставки',
            self::MODULE_NEWS => 'Новости',
        ];

        if (!$withMainPage) {
            unset($array[self::MODULE_MAIN_PAGE]);
        }
        return $array;
    }

    public static function viewsArray($withoutCarousel = false): array
    {
        $array = [
            self::VIEW_CAROUSEL => 'Карусель',
            self::VIEW_TILE => 'Плитка',
            self::VIEW_LINE => 'Строка',
        ];
        if ($withoutCarousel) {
            unset($array[self::VIEW_CAROUSEL]);
        }
        return $array;
    }

    public function getViewName(): string
    {
        return ArrayHelper::getValue(self::viewsArray(), $this->view);
    }

    public static function typesArray($forView = false): array
    {
        $array = [
            self::TYPE_NEW => 'Новинки',
            self::TYPE_POPULAR => 'Популярные',
            self::TYPE_SIMILAR => 'Похожие',
            self::TYPE_HTML => 'Html',
        ];
        if (!$forView) {
            unset($array[self::TYPE_SIMILAR]);
        }
        return $array;
    }

    public function getTypeName(): string
    {
        return ArrayHelper::getValue(self::typesArray(true), $this->type);
    }

    public function afterFind()
    {
        $this->html = $this->html ? Json::decode($this->html) : [];
        $this->htmlCategories = $this->htmlCategories ? Json::decode($this->htmlCategories) : [];
        parent::afterFind();
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if (is_array($this->htmlCategories)) {
            $this->htmlCategories = Json::encode($this->htmlCategories);
        }
        $this->html = is_array($this->html) ? Json::encode($this->html) : $this->html;
        return true;
    }

    public static function getDb()
    {
        return Yii::$app->get('sqlite');
    }

    public static function tableName()
    {
        return 'content_blocks';
    }
}