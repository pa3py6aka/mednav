<?php

namespace core\components\ContentBlocks;


use core\entities\ContentBlock;
use yii\base\Model;

class ContentBlockForm extends Model
{
    public $type;
    public $name;
    public $showTitle;
    public $view = ContentBlock::VIEW_CAROUSEL;
    public $items = 5;
    public $html = [];
    public $htmlCategories;
    public $forModule; // Используется для главной страницы
    public $module;
    public $place;
    public $page;

    public function __construct(ContentBlock $block = null, array $config = [])
    {
        if ($block) {
            $this->type = $block->type;
            $this->name = $block->name;
            $this->showTitle = $block->show_title;
            $this->view = $block->view;
            $this->items = $block->items;
            $this->html = $block->html;
            $this->module = $block->module;
            $this->htmlCategories = $block->htmlCategories;
            $this->forModule = $block->for_module;
            $this->place = $block->place;
            $this->page = $block->page;
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['type', 'showTitle', 'module', 'place'], 'required'],
            [['view', 'items'], 'required', 'when' => function (ContentBlockForm $model) {
                return $model->type != ContentBlock::TYPE_HTML;
            },'whenClient' => "function(attribute, value) {
                  return $('#type-selector').val() != " . ContentBlock::TYPE_HTML . ";
            }"],
            [['type', 'view', 'items', 'module', 'place', 'forModule'], 'integer'],
            [['name'], 'string'],
            ['showTitle', 'boolean'],
            ['html', 'each', 'rule' => ['string']],
            ['htmlCategories', 'each', 'rule' => ['each', 'rule' => ['integer']]],
            ['page', 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'type' => 'Тип вывода',
            'name' => 'Название',
            'showTitle' => 'Выводить на сайте',
            'view' => 'Вид',
            'items' => 'Кол-во контента',
            'html' => 'Html',
            'module' => 'Модуль',
            'htmlCategories' => 'Разделы',
            'place' => 'Место вывода',
            'forModule' => 'Модуль',
        ];
    }
}