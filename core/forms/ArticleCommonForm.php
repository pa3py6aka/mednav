<?php

namespace core\forms;


use core\entities\Article\Article;
use core\entities\News\News;
use yii\base\Model;

class ArticleCommonForm extends Model
{
    public $slug;
    public $user_id;
    public $title;
    public $metaKeywords;
    public $metaDescription;
    public $indirectLinks = 1;

    public $categoryId;
    public $name;
    public $intro;
    public $fullText;
    public $tags;
    public $photos;

    public $article;

    public const SCENARIO_USER_MANAGE = 'userManage';

    /**
     * @param Article|News|null $entity
     * @param array $config
     */
    public function __construct($entity = null, array $config = [])
    {
        if ($entity) {
            $this->slug = $entity->slug;
            $this->user_id = $entity->user_id;
            $this->title = $entity->title;
            $this->metaKeywords = $entity->meta_keywords;
            $this->metaDescription = $entity->meta_description;
            $this->indirectLinks = $entity->indirect_links;
            $this->categoryId = $entity->category_id;
            $this->name = $entity->name;
            $this->intro = $entity->intro;
            $this->fullText = $entity->full_text;
            $this->tags = $entity->getTagsString();

            $this->article = $entity;
        }
        parent::__construct($config);
    }

    public function rules($rules = [])
    {
        return array_merge([
            [['categoryId', 'name', 'fullText'], 'required'],
            [['user_id', 'categoryId'], 'integer'],
            [['slug', 'title', 'metaKeywords', 'name'], 'string', 'max' => 255],
            [['metaDescription', 'intro', 'fullText'], 'string'],
            ['indirectLinks', 'boolean'],
            ['tags', 'string'],
            ['photos', 'each', 'rule' => ['string']],
        ], $rules);
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_USER_MANAGE] = array_diff(array_keys($this->attributes), ['slug', 'user_id', 'title', 'metaKeywords', 'metaDescription', 'indirectLinks']);
        return $scenarios;
    }

    public function beforeValidate()
    {
        if (!$this->slug) {
            $this->slug = $this->name;
        }
        if (is_array($this->categoryId)) {
            $this->categoryId = array_diff($this->categoryId, ['', 0]);
            $categoryId = array_pop($this->categoryId);
            $this->categoryId = $categoryId;
        }
        return parent::beforeValidate();
    }

    public function attributeLabels($attributeLabels = [])
    {
        return array_merge([
            'slug' => 'Slug',
            'user_id' => 'Пользователь',
            'title' => 'Title',
            'metaKeywords' => 'Keywords',
            'metaDescription' => 'Meta description',
            'indirectLinks' => 'Непрямые ссылки',

            'categoryId' => 'Категория',
            'name' => 'Заголовок',
            'intro' => 'Анонс',
            'fullText' => 'Полный текст',
            'tags' => 'Теги',
            'photos' => 'Фотографии',
        ], $attributeLabels);
    }
}