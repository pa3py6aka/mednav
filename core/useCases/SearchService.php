<?php

namespace core\useCases;


use core\components\Settings;
use core\entities\Article\Article;
use core\entities\Board\Board;
use core\entities\Brand\Brand;
use core\entities\CNews\CNews;
use core\entities\Company\Company;
use core\entities\Expo\Expo;
use core\entities\News\News;
use core\entities\SearchInterface;
use core\entities\Trade\Trade;
use Yii;
use yii\base\InvalidArgumentException;
use yii\data\ActiveDataProvider;

class SearchService
{
    public const COMPONENT_BOARD = 'board';
    public const COMPONENT_TRADE = 'trade';
    public const COMPONENT_COMPANY = 'company';
    public const COMPONENT_ARTICLE = 'article';
    public const COMPONENT_BRAND = 'brand';
    public const COMPONENT_CNEWS = 'cnews';
    public const COMPONENT_EXPO = 'expo';
    public const COMPONENT_NEWS = 'news';

    public static function componentsArray(): array
    {
        return [
            self::COMPONENT_TRADE => Yii::$app->settings->get(Settings::TRADE_NAME),
            self::COMPONENT_BOARD => Yii::$app->settings->get(Settings::BOARD_NAME),
            self::COMPONENT_COMPANY => Yii::$app->settings->get(Settings::COMPANY_NAME),
            self::COMPONENT_ARTICLE => Yii::$app->settings->get(Settings::ARTICLE_NAME),
            self::COMPONENT_BRAND => Yii::$app->settings->get(Settings::BRANDS_NAME),
            self::COMPONENT_CNEWS => Yii::$app->settings->get(Settings::CNEWS_NAME),
            self::COMPONENT_EXPO => Yii::$app->settings->get(Settings::EXPO_NAME),
            self::COMPONENT_NEWS => Yii::$app->settings->get(Settings::NEWS_NAME),
        ];
    }

    public function search($component, $text): ActiveDataProvider
    {
        $this->validate($component, $text);

        $class = $this->getComponentClass($component);
        $query = $class::getSearchQuery($text);

        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes' => [
                    'id' => [
                        'asc' => ['id' => SORT_ASC],
                        'desc' => ['id' => SORT_DESC],
                    ],
                ],
            ],
            'pagination' => [
                'pageSizeLimit' => [1, 250],
                'defaultPageSize' => 10,
                'forcePageParam' => false,
            ]
        ]);
    }

    private function validate($component, $text): void
    {
        if (strlen($text) == 0) {
            throw new \DomainException("Введён пустой поисковый запрос.");
        }
        if (!in_array($component, self::componentsArray())) {
            throw new \DomainException("Компонент не найден.");
        }
    }

    /**
     * @param string $component
     * @return string|SearchInterface
     */
    private function getComponentClass($component)
    {
        switch ($component) {
            case self::COMPONENT_BOARD:
                return Board::class;
            case self::COMPONENT_TRADE:
                return Trade::class;
            case self::COMPONENT_COMPANY:
                return Company::class;
            case self::COMPONENT_ARTICLE:
                return Article::class;
            case self::COMPONENT_BRAND:
                return Brand::class;
            case self::COMPONENT_CNEWS:
                return CNews::class;
            case self::COMPONENT_EXPO:
                return Expo::class;
            case self::COMPONENT_NEWS:
                return News::class;
            default: throw new InvalidArgumentException("Компонент не найден.");
        }
    }
}