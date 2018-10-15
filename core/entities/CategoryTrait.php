<?php

namespace core\entities;


use paulzi\nestedsets\NestedSetsBehavior;

trait CategoryTrait
{
    public static function create
    (
        $name,
        $contextName,
        $enabled,
        $notShowOnMain,
        $childrenOnlyParent,
        $slug,
        $metaTitle,
        $metaDescription,
        $metaKeywords,
        $title,
        $descriptionTop,
        $descriptionTopOn,
        $descriptionBottom,
        $descriptionBottomOn,
        $metaTitleItem,
        $metaDescriptionItem,
        $metaTitleOther,
        $metaDescriptionOther,
        $metaKeywordsOther,
        $titleOther,
        $pagination,
        $active
    ): self
    {
        $category = new static();
        $category->name = $name;
        $category->context_name = $contextName;
        $category->enabled = $enabled;
        $category->not_show_on_main = $notShowOnMain;
        $category->children_only_parent = $childrenOnlyParent;
        $category->slug = $slug;
        $category->meta_title = $metaTitle;
        $category->meta_description = $metaDescription;
        $category->meta_keywords = $metaKeywords;
        $category->title = $title;
        $category->description_top = $descriptionTop;
        $category->description_top_on = $descriptionTopOn;
        $category->description_bottom = $descriptionBottom;
        $category->description_bottom_on = $descriptionBottomOn;
        $category->meta_title_item = $metaTitleItem;
        $category->meta_description_item = $metaDescriptionItem;
        $category->meta_title_other = $metaTitleOther;
        $category->meta_description_other = $metaDescriptionOther;
        $category->meta_keywords_other = $metaKeywordsOther;
        $category->title_other = $titleOther;
        $category->pagination = $pagination;
        $category->active = $active;
        return $category;
    }

    public function edit
    (
        $name,
        $contextName,
        $enabled,
        $notShowOnMain,
        $childrenOnlyParent,
        $slug,
        $metaTitle,
        $metaDescription,
        $metaKeywords,
        $title,
        $descriptionTop,
        $descriptionTopOn,
        $descriptionBottom,
        $descriptionBottomOn,
        $metaTitleItem,
        $metaDescriptionItem,
        $metaTitleOther,
        $metaDescriptionOther,
        $metaKeywordsOther,
        $titleOther,
        $pagination,
        $active
    ): void
    {
        $this->name = $name;
        $this->context_name = $contextName;
        $this->enabled = $enabled;
        $this->not_show_on_main = $notShowOnMain;
        $this->children_only_parent = $childrenOnlyParent;
        $this->slug = $slug;
        $this->meta_title = $metaTitle;
        $this->meta_description = $metaDescription;
        $this->meta_keywords = $metaKeywords;
        $this->title = $title;
        $this->description_top = $descriptionTop;
        $this->description_top_on = $descriptionTopOn;
        $this->description_bottom = $descriptionBottom;
        $this->description_bottom_on = $descriptionBottomOn;
        $this->meta_title_item = $metaTitleItem;
        $this->meta_description_item = $metaDescriptionItem;
        $this->meta_title_other = $metaTitleOther;
        $this->meta_description_other = $metaDescriptionOther;
        $this->meta_keywords_other = $metaKeywordsOther;
        $this->title_other = $titleOther;
        $this->pagination = $pagination;
        $this->active = $active;
    }

    public function getTitle(): string
    {
        return $this->title ?: $this->name;
    }

    public function behaviors() {
        return [
            [
                'class' => NestedSetsBehavior::class,
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'context_name' => 'Контекстное название раздела',
            'enabled' => 'Доступен для добавления объявлений',
            'not_show_on_main' => 'Не выводить на главной',
            'children_only_parent' => 'Выводить дочерние разделы только в родителе',
            'slug' => 'Алиас',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'title' => 'Заголовок',
            'description_top' => 'Описание вверху',
            'description_top_on' => 'Описание вверху только на гл. стр.',
            'description_bottom' => 'Описание внизу',
            'description_bottom_on' => 'Описание внизу только на гл. стр.',
            'meta_title_item' => 'Meta Title Item',
            'meta_description_item' => 'Meta Description Item',
            'title_other' => 'Заголовок для остальных стр.',
            'pagination' => 'Пагинация',
            'active' => 'Показывать',
            'tree' => 'Tree',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'depth' => 'Depth',
        ];
    }
}