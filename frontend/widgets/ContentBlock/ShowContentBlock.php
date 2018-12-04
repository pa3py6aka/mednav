<?php

namespace frontend\widgets\ContentBlock;


use core\entities\Board\Board;
use core\entities\CategoryInterface;
use core\entities\Company\Company;
use core\entities\Company\CompanyCategoryAssignment;
use core\entities\ContentBlock;
use core\entities\Trade\Trade;
use yii\base\InvalidArgumentException;
use yii\base\Widget;

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

    public function run()
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

            $result[] = $this->render($placeView . '-' . $view, [
                'block' => $block,
                'items' => $this->getItems($block),
            ]);
        }

        return implode("\n", $result);
    }

    private function getItems(ContentBlock $block)
    {
        if ($block->type == ContentBlock::TYPE_HTML) {
            return $block->html;
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
            $words = array_map(function ($val) {
                return ' ' . $val . ' ';
            }, $words);

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

        if ($this->category) {
            $categoryIds = array_merge([$this->category->id], $this->category->getDescendants()->select('id')->column());
            if ($this->module === ContentBlock::MODULE_COMPANY) {
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
            $query = Board::find()->with('mainPhoto', 'author.geo', 'author.company.geo');
        } else if ($module == ContentBlock::MODULE_TRADE) {
            $query = Trade::find()->with('mainPhoto', 'company.geo');
        } else if ($module == ContentBlock::MODULE_COMPANY) {
            $query = Company::find()->with('mainPhoto');
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
            } else {
                $url = $item->author->getUrl();
                $name = $item->author->getVisibleName();
                $geo = $item->geo->name;
            }
            ?>
            <div class="sidebar-item-vendinfo">
                <a href="<?= $url ?>"><?= $name ?></a> / <?= $geo ?>
            </div>
            <?php
        }
    }
}