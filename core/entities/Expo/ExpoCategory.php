<?php

namespace core\entities\Expo;

use core\entities\Article\common\ArticleCategoryQueryCommon;
use core\entities\CategoryInterface;
use core\entities\CategoryTrait;
use paulzi\nestedsets\NestedSetsBehavior;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%exposition_categories}}".
 *
 * @property int $id
 *
 * @property Expo[] $expos
 *
 * @property ExpoCategory $parent
 * @property ExpoCategory[] $parents
 * @property ExpoCategory[] $children
 * @property ExpoCategory $prev
 * @property ExpoCategory $next
 * @mixin NestedSetsBehavior
 * @method bool isRoot
 */
class ExpoCategory extends \yii\db\ActiveRecord implements CategoryInterface
{
    use CategoryTrait;

    public function getElementsCount(): int
    {
        $ids = array_merge($this->getDescendants()->select('id')->column(), [$this->id]);
        return Expo::find()->where(['category_id' => $ids])->count();
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%exposition_categories}}';
    }

    public function getExpos(): ActiveQuery
    {
        return $this->hasMany(Expo::class, ['category_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ArticleCategoryQueryCommon the active query used by this AR class.
     */
    public static function find()
    {
        return new ArticleCategoryQueryCommon(get_called_class());
    }
}
