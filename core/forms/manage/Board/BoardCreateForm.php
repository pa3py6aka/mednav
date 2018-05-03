<?php

namespace core\forms\manage\Board;


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
            [['description', 'keywords', 'fullText'], 'string'],
            ['note', 'string', 'max' => 100],
            ['priceFrom', 'boolean'],
            [['categoryId', 'params'], 'each', 'rule' => ['integer']],
            ['photos', 'each', 'rule' => ['string']],
            ['price', 'match', 'pattern' => '/^[0-9]+((\.|,)[0-9]+)?$/uis']
        ];
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
            'termId' => 'Срок размещения',
            'geoId' => 'Регион',
            'params' => 'Параметры',
            'photos' => 'Фотографии',
        ];
    }
}