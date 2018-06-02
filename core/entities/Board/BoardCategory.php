<?php

namespace core\entities\Board;


use core\entities\Board\queries\BoardCategoryQuery;
use paulzi\nestedsets\NestedSetsBehavior;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%board_categories}}".
 *
 * @property int $id
 * @property string $name
 * @property string $context_name
 * @property int $enabled
 * @property int $not_show_on_main
 * @property int $children_only_parent
 * @property string $slug
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $title
 * @property string $description_top
 * @property int $description_top_on
 * @property string $description_bottom
 * @property int $description_bottom_on
 * @property string $meta_title_item
 * @property string $meta_description_item
 * @property int $pagination
 * @property int $active
 *
 * @property int $tree
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 *
 * @property BoardCategoryRegion[] $regions
 * @property BoardCategoryParameter[] $parameters
 *
 * @property BoardCategory $parent
 * @property BoardCategory[] $parents
 * @property BoardCategory[] $children
 * @property BoardCategory $prev
 * @property BoardCategory $next
 * @mixin NestedSetsBehavior
 */
class BoardCategory extends ActiveRecord
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
        $pagination,
        $active
    ): BoardCategory
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
        $this->pagination = $pagination;
        $this->active = $active;
    }

    public function getTitle()
    {
        return $this->title ?: $this->name;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%board_categories}}';
    }

    public function behaviors() {
        return [
            [
                'class' => NestedSetsBehavior::class,
                'treeAttribute' => 'tree',
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
     * @inheritdoc

    public function rules()
    {
        return [
            [['name', 'slug', 'meta_description', 'meta_keywords', 'description_top', 'description_bottom', 'meta_description_item', 'lft', 'rgt', 'depth'], 'required'],
            [['meta_description', 'meta_keywords', 'description_top', 'description_bottom', 'meta_description_item'], 'string'],
            [['tree', 'lft', 'rgt', 'depth'], 'integer'],
            [['name', 'context_name', 'slug', 'meta_title', 'title', 'meta_title_item'], 'string', 'max' => 255],
            [['enabled', 'not_show_on_main', 'children_only_parent', 'description_top_on', 'description_bottom_on', 'active'], 'string', 'max' => 1],
            [['pagination'], 'string', 'max' => 3],
            [['slug'], 'unique'],
        ];
    }*/

    /**
     * @inheritdoc
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
            'pagination' => 'Пагинация',
            'active' => 'Показывать',
            'tree' => 'Tree',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'depth' => 'Depth',
        ];
    }

    public function getRegions(): ActiveQuery
    {
        return $this->hasMany(BoardCategoryRegion::class, ['category_id' => 'id']);
    }

    public function getParameters(): ActiveQuery
    {
        return $this->hasMany(BoardCategoryParameter::class, ['category_id' => 'id']);
    }

    /**
     * @return BoardCategoryParameter[]|array
     */
    public function getParametersForForm()
    {
        return $this->getParameters()->with('parameter.boardParameterOptions')->all();
    }

    /**
     * @inheritdoc
     * @return BoardCategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BoardCategoryQuery(get_called_class());
    }
}
