<?php

namespace core\forms\manage\Board;


use core\entities\Board\BoardCategory;
use yii\base\Model;

class BoardCreateForm extends Model
{
    public $authorId;
    public $name;
    public $slug;
    public $categoryId;
    public $title;
    public $description;
    public $keywords;
    public $note;
    public $price;
    public $currency;
    public $priceFrom;
    public $fullText;
    public $tags;
    public $termId;
    public $geoId;
    public $params;
    public $photos;

    public function rules()
    {
        return [
            [['name', 'categoryId', 'price', 'currency', 'fullText', 'termId', 'geoId'], 'required'],
            [['name', 'slug', 'title'], 'string', 'max' => 255],
            [['authorId', 'currency', 'termId', 'geoId'], 'integer'],
            [['description', 'keywords', 'fullText', 'tags'], 'string'],
            ['note', 'string', 'max' => 100],
            ['priceFrom', 'boolean'],
            ['categoryId', 'integer'],
            ['categoryId', 'exist', 'targetClass' => BoardCategory::class, 'targetAttribute' => 'id'],
            [['params'], 'each', 'rule' => ['safe']],
            ['photos', 'each', 'rule' => ['string']],
            ['price', 'match', 'pattern' => '/^[0-9]+((\.|,)[0-9]+)?$/uis']
        ];
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
            'authorId' => 'Автор',
            'name' => 'Название',
            'slug' => 'Slug',
            'categoryId' => 'Раздел',
            'title' => 'Title',
            'description' => 'Description',
            'keywords' => 'Keywords',
            'note' => 'Примечание',
            'price' => 'Цена',
            'currency' => 'Ден. единица',
            'priceFrom' => 'Цена от',
            'fullText' => 'Полное описание',
            'tags' => 'Теги',
            'termId' => 'Срок размещения',
            'geoId' => 'Регион',
            'params' => 'Параметры',
            'photos' => 'Фотографии',
        ];
    }
}