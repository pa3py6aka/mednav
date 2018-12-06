<?php

namespace core\entities;

use core\behaviors\SluggableBehavior;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%pages}}".
 *
 * @property int $id
 * @property int $type
 * @property string $name
 * @property string $content
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $slug
 */
class Page extends ActiveRecord
{
    public const TYPE_UCP_PAGE = 1;

    public $userSlug;

    public static function create($type, $name, $content, $metaTitle, $metaDescription, $metaKeywords, $slug): Page
    {
        $page = new self();
        $page->type = $type;
        $page->name = $name;
        $page->content = $content;
        $page->meta_title = $metaTitle;
        $page->meta_description = $metaDescription;
        $page->meta_keywords = $metaKeywords;
        $page->userSlug = $slug;
        return $page;
    }

    public function edit($name, $content, $metaTitle, $metaDescription, $metaKeywords, $slug): void
    {
        $this->name = $name;
        $this->content = $content;
        $this->meta_title = $metaTitle;
        $this->meta_description = $metaDescription;
        $this->meta_keywords = $metaKeywords;
        if ($slug) {
            $this->slug = $slug;
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%pages}}';
    }

    public function behaviors(): array
    {
        return [
            SluggableBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}

    public function rules()
    {
        return [
            [['type', 'name', 'content', 'meta_title', 'meta_description', 'meta_keywords', 'slug'], 'required'],
            [['type'], 'integer'],
            [['content', 'meta_description', 'meta_keywords'], 'string'],
            [['name', 'meta_title'], 'string', 'max' => 255],
            [['slug'], 'string', 'max' => 100],
            [['slug'], 'unique'],
        ];
    } */

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Тип',
            'name' => 'Название',
            'content' => 'Контент',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'slug' => 'Slug',
        ];
    }
}
