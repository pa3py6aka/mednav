<?php

namespace core\forms\manage;


use core\entities\Board\BoardCategoryRegion;
use core\entities\Company\CompanyCategoryRegion;
use yii\base\Model;

class CategoryRegionForm extends Model
{
    public $metaTitle;
    public $metaDescription;
    public $metaKeywords;
    public $title;
    public $descriptionTop;
    public $descriptionTopOn;
    public $descriptionBottom;
    public $descriptionBottomOn;

    private $_region;

    /**
     * @param null|BoardCategoryRegion|CompanyCategoryRegion $region
     * @param array $config
     */
    public function __construct($region = null, $config = [])
    {
        if ($region) {
            $this->metaTitle = $region->meta_title;
            $this->metaDescription = $region->meta_description;
            $this->metaKeywords = $region->meta_keywords;
            $this->title = $region->title;
            $this->descriptionTop = $region->description_top;
            $this->descriptionTopOn = $region->description_top_on;
            $this->descriptionBottom = $region->description_bottom;
            $this->descriptionBottomOn = $region->description_bottom_on;

            $this->_region = $region;
        }

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['metaTitle', 'title'], 'string', 'max' => 255],
            [['descriptionTopOn', 'descriptionBottomOn'], 'boolean'],
            [['metaDescription', 'metaKeywords', 'descriptionTop', 'descriptionBottom'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'metaTitle' => 'Meta Title',
            'title' => 'Заголовок',
            'descriptionTop' => 'Описание вверху',
            'descriptionBottom' => 'Описание внизу',
            'descriptionTopOn' => 'Только на гл. стр.',
            'descriptionBottomOn' => 'Только на гл. стр.',
        ];
    }
}