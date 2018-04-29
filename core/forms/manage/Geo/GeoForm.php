<?php

namespace core\forms\manage\Geo;


use core\entities\Geo;
use core\validators\SlugValidator;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class GeoForm extends Model
{
    public $name;
    public $slug;
    public $popular;
    public $active;
    public $parentId;

    private $_geo;

    public function __construct(Geo $geo = null, $config = [])
    {
        if ($geo) {
            $this->name = $geo->name;
            $this->slug = $geo->slug;
            $this->popular = $geo->popular;
            $this->active = $geo->active;

            $this->parentId = $geo->parent ? $geo->parent->id : null;
            $this->_geo = $geo;
        }

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['name', 'slug', 'popular', 'active'], 'required'],
            [['parentId'], 'integer'],
            [['name', 'slug'], 'string', 'max' => 255],
            [['popular', 'active'], 'boolean'],
            ['slug', SlugValidator::class],
            [['slug'], 'unique', 'targetClass' => Geo::class, 'filter' => $this->_geo ? ['<>', 'id', $this->_geo->id] : null],
        ];
    }

    public static function parentCategoriesList($withRoot = true)
    {
        $geos = ArrayHelper::map(Geo::find()->orderBy('lft')->asArray()->all(), 'id', function (array $geo) {
            return ($geo['depth'] > 1 ? str_repeat('-', $geo['depth'] - 1) . ' ' : '') . ($geo['id'] == 1 ? 'Это страна' : $geo['name']);
        });

        if (!$withRoot) {
            reset($geos);
            $key = key($geos);
            unset($geos[$key]);
        }

        return $geos;
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'slug' => 'URL',
            'popular' => 'Популярный',
            'active' => 'Показывать',
            'parentId' => 'Родитель',
        ];
    }
}