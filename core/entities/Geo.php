<?php

namespace core\entities;

use core\entities\queries\GeoQuery;
use paulzi\nestedsets\NestedSetsBehavior;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%geo}}".
 *
 * @property int $id
 * @property string $name
 * @property string $name_p [varchar(255)]
 * @property string $slug
 * @property int $popular
 * @property int $active
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 *
 * @property string $nameP
 *
 * @property Geo $parent
 * @property Geo[] $parents
 * @property Geo[] $children
 * @property Geo $prev
 * @property Geo $next
 * @mixin NestedSetsBehavior
 */
class Geo extends ActiveRecord
{
    public static function create($name, $name_p, $slug, $popular, $active): Geo
    {
        $geo = new static();
        $geo->name = $name;
        $geo->name_p = $name_p;
        $geo->slug = $slug;
        $geo->popular = $popular;
        $geo->active = $active;
        return $geo;
    }

    public function edit($name, $name_p, $slug, $popular, $active): void
    {
        $this->name = $name;
        $this->name_p = $name_p;
        $this->slug = $slug;
        $this->popular = $popular;
        $this->active = $active;
    }

    public function getNameP(): string
    {
        return $this->name_p ?: $this->name;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%geo}}';
    }

    public function behaviors()
    {
        return [
            NestedSetsBehavior::class,
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
            [['name', 'slug'], 'required'],
            [['lft', 'rgt', 'depth'], 'integer'],
            [['name', 'slug'], 'string', 'max' => 255],
            [['popular', 'active'], 'boolean'],
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
            'name_p' => 'Название в предложном падеже',
            'slug' => 'URL',
            'popular' => 'Популярный',
            'active' => 'Показывать',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'depth' => 'Depth',
        ];
    }

    /**
     * @inheritdoc
     * @return GeoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GeoQuery(static::class);
    }
}
