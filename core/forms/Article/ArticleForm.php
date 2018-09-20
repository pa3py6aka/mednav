<?php

namespace core\forms\Article;


use core\entities\Article\Article;
use yii\base\Model;

class ArticleForm extends Model
{
    public $slug;
    public $user_id;
    public $title;
    public $metaKeywords;
    public $metaDescription;
    public $indirectLinks = 1;

    public $categoryId = [];
    public $name;
    public $intro;
    public $fullText;
    public $tags;
    public $photos;

    public $article;

    public const SCENARIO_USER_MANAGE = 'userManage';

    public function __construct(Article $article = null, array $config = [])
    {
        if ($article) {
            $this->slug = $article->slug;
            $this->user_id = $article->user_id;
            $this->title = $article->title;
            $this->metaKeywords = $article->meta_keywords;
            $this->metaDescription = $article->meta_description;
            $this->indirectLinks = $article->indirect_links;

            foreach ($article->category->parents as $parent) {
                if (!$parent->isRoot()) {
                    $this->categoryId[] = $parent->id;
                }
            }
            $this->categoryId[] = $article->category_id;
            $this->name = $article->name;
            $this->intro = $article->intro;
            $this->fullText = $article->full_text;
            $this->tags = $article->getTagsString();

            $this->article = $article;
        } else {
            $this->categoryId[] = '';
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['categoryId', 'name', 'fullText'], 'required'],
            [['user_id', 'categoryId'], 'integer'],
            [['slug', 'title', 'metaKeywords', 'name'], 'string', 'max' => 255],
            [['metaDescription', 'intro', 'fullText'], 'string'],
            ['indirectLinks', 'boolean'],
            ['tags', 'string'],
            ['photos', 'each', 'rule' => ['string']],
        ];
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

    public function attributeLabels()
    {
        return [
            'slug' => 'Slug',
            'user_id' => 'Пользователь',
            'title' => 'Title',
            'metaKeywords' => 'Keywords',
            'metaDescription' => 'Meta description',
            'indirectLinks' => 'Непрямые ссылки',

            'categoryId' => 'Категория',
            'name' => 'Название',
            'intro' => 'Интро-текст',
            'fullText' => 'Полный текст',
            'tags' => 'Теги',
            'photos' => 'Фотографии',
        ];
    }
}