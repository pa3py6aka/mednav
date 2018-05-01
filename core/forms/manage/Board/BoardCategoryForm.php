<?php

namespace core\forms\manage\Board;


use core\entities\Board\BoardCategory;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

class BoardCategoryForm extends Model
{
    public $name;
    public $contextName;
    public $enabled;
    public $notShowOnMain;
    public $childrenOnlyParent;
    public $slug;
    public $metaTitle;
    public $metaDescription;
    public $metaKeywords;
    public $title;
    public $descriptionTop;
    public $descriptionTopOn;
    public $descriptionBottom;
    public $descriptionBottomOn;
    public $metaTitleItem;
    public $metaDescriptionItem;
    public $pagination;
    public $active;

    public $parentId;

    private $_category;

    public function __construct(BoardCategory $category = null, $config = [])
    {
        if ($category) {
            $this->name = $category->name;
            $this->contextName = $category->context_name;
            $this->enabled = $category->enabled;
            $this->notShowOnMain = $category->not_show_on_main;
            $this->childrenOnlyParent = $category->children_only_parent;
            $this->slug = $category->slug;
            $this->metaTitle = $category->meta_title;
            $this->metaDescription = $category->meta_description;
            $this->metaKeywords = $category->meta_keywords;
            $this->title = $category->title;
            $this->descriptionTop = $category->description_top;
            $this->descriptionTopOn = $category->description_top_on;
            $this->descriptionBottom = $category->description_bottom;
            $this->descriptionBottomOn = $category->description_bottom_on;
            $this->metaTitleItem = $category->meta_title_item;
            $this->metaDescriptionItem = $category->meta_description_item;
            $this->pagination = $category->pagination;
            $this->active = $category->active;

            $this->parentId = $category->parent ? $category->parent->id : null;
            $this->_category = $category;
        }

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['parentId'], 'integer'],
            [['name', 'contextName', 'slug', 'metaTitle', 'title', 'metaTitleItem'], 'string', 'max' => 255],
            [['enabled', 'active', 'notShowOnMain', 'childrenOnlyParent', 'descriptionTopOn', 'descriptionBottomOn'], 'boolean'],
            ['pagination', 'integer'],
            [['metaDescription', 'metaKeywords', 'descriptionTop', 'descriptionBottom', 'metaDescriptionItem'], 'string'],
            [['slug'], 'unique', 'targetClass' => BoardCategory::class, 'filter' => $this->_category ? ['<>', 'id', $this->_category->id] : null],
        ];
    }

    public function beforeValidate()
    {
        $this->slug = Inflector::slug($this->slug);
        return parent::beforeValidate();
    }

    public static function parentCategoriesList()
    {
        $categories = ArrayHelper::map(BoardCategory::find()->orderBy('lft')->asArray()->all(), 'id', function (array $category) {
            return ($category['depth'] > 0 ? str_repeat('-', $category['depth'] - 1) . ' ' : '') . $category['name'];
        });

        return $categories;
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'contextName' => 'Контекстное название раздела',
            'enabled' => 'Доступен для добавления объявлений',
            'notShowOnMain' => 'Не выводить на главной',
            'childrenOnlyParent' => 'Выводить дочерние разделы только в родителе',
            'slug' => 'Алиас',
            'metaTitle' => 'Meta Title',
            'title' => 'Заголовок',
            'descriptionTop' => 'Описание вверху',
            'descriptionBottom' => 'Описание внизу',
            'descriptionTopOn' => 'Только на гл. стр.',
            'descriptionBottomOn' => 'Только на гл. стр.',
            'pagination' => 'Пагинация',
            'active' => 'Показывать',
            'parentId' => 'Родитель',
        ];
    }
}