<?php

namespace core\forms\manage;


use core\entities\Board\BoardCategory;
use core\entities\Company\CompanyCategory;
use core\entities\Trade\TradeCategory;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

class CategoryForm extends Model
{
    public $name;
    public $contextName;
    public $enabled = 1;
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
    public $metaTitleOther;
    public $metaDescriptionOther;
    public $metaKeywordsOther;
    public $titleOther;
    public $pagination;
    public $active;

    public $parentId;

    protected $_category;

    private $_entityClass;

    /**
     * @param $entityClass
     * @param null|BoardCategory|CompanyCategory $category
     * @param array $config
     */
    public function __construct($entityClass, $category = null, $config = [])
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
            $this->metaTitleOther = $category->meta_title_other;
            $this->metaDescriptionOther = $category->meta_description_other;
            $this->metaKeywordsOther = $category->meta_keywords_other;
            $this->titleOther = $category->title_other;
            $this->pagination = $category->pagination;
            $this->active = $category->active;

            $this->parentId = $category->parent ? $category->parent->id : null;
            $this->_category = $category;
        }

        $this->_entityClass = $entityClass;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name', 'parentId'], 'required'],
            [['parentId'], 'integer'],
            [['name', 'contextName', 'slug', 'metaTitle', 'title', 'metaTitleItem'], 'string', 'max' => 255],
            [['enabled', 'active', 'notShowOnMain', 'childrenOnlyParent', 'descriptionTopOn', 'descriptionBottomOn'], 'boolean'],
            ['pagination', 'integer'],
            [['metaDescription', 'metaKeywords', 'descriptionTop', 'descriptionBottom', 'metaDescriptionItem', 'metaTitleOther', 'metaDescriptionOther', 'metaKeywordsOther', 'titleOther'], 'string'],
            [['slug'], 'unique', 'targetClass' => $this->_entityClass, 'filter' => $this->_category ? ['<>', 'id', $this->_category->id] : null],
        ];
    }

    public function beforeValidate()
    {
        $this->slug = $this->slug ? Inflector::slug($this->slug) : Inflector::slug($this->name);
        return parent::beforeValidate();
    }

    /**
     * @param string|BoardCategory|CompanyCategory|TradeCategory $class
     * @param bool $onlyEnabled
     * @return array
     */
    public static function parentCategoriesList($class, $onlyEnabled = false, $withRoot = true): array
    {
        $query = $class::find()->orderBy('lft');
        if ($onlyEnabled) {
            $query->active()->enabled();
        }
        if (!$withRoot) {
            $query->andWhere(['>', 'depth', 0]);
        }
        $categories = ArrayHelper::map($query->asArray()->all(), 'id', function (array $category) {
            return ($category['depth'] > 1 ? str_repeat('-', $category['depth'] - 1) . ' ' : '') . $category['name'];
        });

        return array_merge([0 => 'Главная страница'], $categories);
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
            'titleOther' => 'Заголовок для остальных стр.',
            'pagination' => 'Пагинация',
            'active' => 'Показывать',
            'parentId' => 'Родитель',
        ];
    }
}