<?php

namespace core\forms;


use core\entities\Page;
use core\validators\SlugValidator;
use yii\base\Model;

class PageForm extends Model
{
    public $name;
    public $slug;
    public $metaTitle;
    public $metaDescription;
    public $metaKeywords;
    public $content;

    private $_page;

    public function __construct(Page $page = null, array $config = [])
    {
        if ($page) {
            $this->name = $page->name;
            $this->slug = $page->slug;
            $this->metaTitle = $page->meta_title;
            $this->metaDescription = $page->meta_description;
            $this->metaKeywords = $page->meta_keywords;
            $this->content = $page->content;

            $this->_page = $page;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name', 'content'], 'required'],
            [['name', 'metaTitle'], 'string', 'max' => 255],
            [['content', 'metaDescription', 'metaKeywords'], 'string'],
            ['slug', 'string', 'max' => 100],
            ['slug', SlugValidator::class],
            ['slug', 'unique', 'targetClass' => Page::class, 'filter' => ['<>', 'id', $this->_page ? $this->_page->id : null]]
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Название страницы',
            'slug' => 'Url',
            'content' => 'Контент',
        ];
    }
}