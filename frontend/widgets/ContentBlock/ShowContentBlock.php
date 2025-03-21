<?php

namespace frontend\widgets\ContentBlock;


use core\entities\Article\Article;
use core\entities\Board\Board;
use core\entities\Brand\Brand;
use core\entities\CategoryInterface;
use core\entities\CNews\CNews;
use core\entities\Company\Company;
use core\entities\Company\CompanyCategoryAssignment;
use core\entities\ContentBlock;
use core\entities\Expo\Expo;
use core\entities\News\News;
use core\entities\Trade\Trade;
use Yii;
use yii\base\InvalidArgumentException;
use yii\base\Widget;
use yii\caching\ExpressionDependency;
use yii\caching\TagDependency;
use yii\helpers\ArrayHelper;

class ShowContentBlock extends Widget
{
    public $module;
    public $place;
    public $page;
    public $start = 1; // С какого блока начинать(номер блока)
    public $count; // Сколько блоков вывести(null = все начиная со $start)

    /* @var CategoryInterface|null */
    public $category; // Раздел

    /* @var Board|Trade|Company|null */
    public $entity; // Объявление/товар/компания если виджет на странице контента

    const CACHE_KEY_PREFIX = 'ConBlock-';

    public function run()
    {
        $key = md5($this->module . $this->place . $this->page . $this->start . $this->count . ($this->category ? $this->category->id : 'NULL') . ($this->entity ? $this->entity->id : 'NULL'));
        $cacheKey = self::CACHE_KEY_PREFIX . $key;
        return Yii::$app->cache->getOrSet($cacheKey, function () {
            return $this->renderContent();
        }, 60);
        //return $this->renderContent();
    }

    private function renderContent(): string
    {
        $query = ContentBlock::find()
            ->where(['module' => $this->module, 'place' => $this->place, 'page' => $this->page])
            ->orderBy(['sort' => SORT_ASC])
            ->limit($this->count ?: 100)
            ->offset($this->start - 1);

        $result = [];
        foreach ($query->all() as $block) {
            if (!$block->enable) {
                continue;
            }
            if ($block->view == ContentBlock::VIEW_CAROUSEL) {
                $view = 'carousel';
            } else if ($block->view == ContentBlock::VIEW_TILE) {
                $view = 'tile';
            } else if ($block->view == ContentBlock::VIEW_LINE) {
                $view = 'line';
            } else if ($block->type != ContentBlock::TYPE_HTML) {
                throw new InvalidArgumentException("Неверное значение 'Вид блока'.");
            } else {
                $view = 'line';
            }

            if ($block->place == ContentBlock::PLACE_MAIN) {
                $placeView = 'main';
            } else {
                $placeView = 'sidebar';
            }

            $items = $this->getItems($block);
            if ($items) {
                $result[] = $this->render($placeView . '-' . $view, [
                    'block' => $block,
                    'items' => $items,
                ]);
            }
        }

        return implode("\n", $result);
    }

    private function getItems(ContentBlock $block)
    {
        if ($block->type === ContentBlock::TYPE_HTML) {
            if ($block->module === ContentBlock::MODULE_MAIN_PAGE) {
                return $block->html;
            }
            $needle = $this->category ? $this->category->id : 0;
            $result = [];
            foreach ($block->html as $k => $html) {
                $htmlCats = ArrayHelper::getValue($block->htmlCategories, $k, []);
                if (\is_array($htmlCats) && \in_array($needle, $htmlCats)) {
                    $result[] = $html;
                }
            }
            return $result;
        }

        $module = $block->for_module;
        $query = $this->getQuery($module);

        if ($block->type == ContentBlock::TYPE_NEW) {
            $query->orderBy(['ent.id' => SORT_DESC]);
        } else if ($block->type == ContentBlock::TYPE_POPULAR) {
            $query->orderBy(['ent.views' => SORT_ASC]);
        } else if ($block->type == ContentBlock::TYPE_SIMILAR) {
            $entity = $this->entity;
            $words = explode(' ', $entity->name);
            $words = array_map('trim', $words);
            $words = array_filter($words, function ($val) {
                return mb_strlen($val) > 2;
            });
            /*$words = array_map(function ($val) {
                return ' ' . $val . ' ';
            }, $words);*/

            $query->andWhere(['or like', 'ent.name', $words]);
            $query->orderBy(['ent.id' => SORT_DESC]);

            /*$tags = $entity->getTags()->select('name')->column();
            $query->joinWith('tags t');
            $likes = [];
            foreach ($tags as $tag) {
                $likes[] = ['like', 't.name', $tag];
            }
            $query->andWhere(array_merge(['or'], $likes));
            $query->orderBy(['ent.id' => SORT_DESC]);*/
        }

        if ($this->entity) {
            $query->andWhere(['<>', 'ent.id', $this->entity->id]);
        }

        if ($this->category && $module == $this->module) {
            $categoryIds = array_merge([$this->category->id], $this->category->getDescendants()->select('id')->column());
            if ($module === ContentBlock::MODULE_COMPANY) {
                $query->leftJoin(CompanyCategoryAssignment::tableName() . ' cca', 'cca.company_id=ent.id')
                    ->andWhere(['cca.category_id' => $categoryIds]);
            } else {
                $query->andWhere(['ent.category_id' => $categoryIds]);
            }
        }

        return $query->limit($block->items)->all();
    }

    private function getQuery($module)
    {
        if ($module == ContentBlock::MODULE_BOARD) {
            $query = Board::find()->with('mainPhoto', 'author.geo', 'author.company.geo', 'currency');
        } else if ($module == ContentBlock::MODULE_TRADE) {
            $query = Trade::find()->with('mainPhoto', 'company.geo', 'userCategory.currency');
        } else if ($module == ContentBlock::MODULE_COMPANY) {
            $query = Company::find()->with('mainPhoto');
        } else if ($module == ContentBlock::MODULE_ARTICLE) {
            $query = Article::find()->with('mainPhoto');
        } else if ($module == ContentBlock::MODULE_BRAND) {
            $query = Brand::find()->with('mainPhoto');
        } else if ($module == ContentBlock::MODULE_CNEWS) {
            $query = CNews::find()->with('mainPhoto');
        } else if ($module == ContentBlock::MODULE_EXPO) {
            $query = Expo::find()->with('mainPhoto');
        } else if ($module == ContentBlock::MODULE_NEWS) {
            $query = News::find()->with('mainPhoto');
        } else {
            throw new InvalidArgumentException("Неверный модуль контентного блока.");
        }
        return $query->active()->alias('ent');
    }

    public static function getVendInfo(ContentBlock $block, $item)
    {
        /* @var $item Board|Trade */
        if ($block->for_module != ContentBlock::MODULE_COMPANY){
            if ($item instanceof Trade) {
                $url = $item->company->getUrl();
                $name = $item->company->getFullName();
                $geo = $item->company->geo->name;
            } else if ($item instanceof Board) {
                $url = $item->author->getUrl();
                $name = $item->author->getVisibleName();
                $geo = $item->geo->name;
            } else {
                return;
            }
            ?>
            <div class="sidebar-item-vendinfo">
                <a href="<?= $url ?>"><?= $name ?></a> / <?= $geo ?>
            </div>
            <?php
        }
    }
}