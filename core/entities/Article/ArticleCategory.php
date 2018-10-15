<?php

namespace core\entities\Article;

use core\entities\Article\queries\ArticleCategoryQuery;
use core\entities\CategoryTrait;
use core\entities\Trade\queries\TradeCategoryQuery;
use paulzi\nestedsets\NestedSetsBehavior;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%article_categories}}".
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
 * @property string $meta_title_other [varchar(255)]
 * @property string $meta_description_other
 * @property string $meta_keywords_other
 * @property string $title_other [varchar(255)]
 * @property int $pagination
 * @property int $active
 *
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 *
 * @property Article[] $articles
 *
 * @property ArticleCategory $parent
 * @property ArticleCategory[] $parents
 * @property ArticleCategory[] $children
 * @property ArticleCategory $prev
 * @property ArticleCategory $next
 *
 * @mixin NestedSetsBehavior
 */
class ArticleCategory extends \yii\db\ActiveRecord
{
    use CategoryTrait;

    public function getElementsCount(): int
    {
        $ids = array_merge($this->getDescendants()->select('id')->column(), [$this->id]);
        return Article::find()->where(['category_id' => $ids])->count();
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%article_categories}}';
    }

    public function getArticles(): ActiveQuery
    {
        return $this->hasMany(Article::class, ['category_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ArticleCategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ArticleCategoryQuery(get_called_class());
    }
}
