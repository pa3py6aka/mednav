<?php

namespace core\forms\Expo;


use core\entities\Expo\Expo;
use core\forms\ArticleCommonForm;
use Yii;

class ExpoForm extends ArticleCommonForm
{
    public $showDates;
    public $startDate;
    public $endDate;
    public $city;

    public function __construct(Expo $expo = null, array $config = [])
    {
        if ($expo) {
            $this->showDates = $expo->show_dates;
            $this->startDate = Yii::$app->formatter->asDate($expo->start_date);
            $this->endDate = Yii::$app->formatter->asDate($expo->end_date);
            $this->city = $expo->city;
        }
        parent::__construct($expo, $config);
    }

    public function rules($rules = [])
    {
        $rules = [
            ['showDates', 'required'],
            ['showDates', 'boolean'],
            [['startDate', 'endDate'], 'trim'],
            [['startDate', 'endDate'], 'required'],
            [['startDate', 'endDate'], 'datetime', 'format' => 'php:d.m.Y'],
            ['endDate', 'expoDatesValidate'],
            ['city', 'string', 'max' => 255],
            ['city', 'trim'],
        ];
        return parent::rules($rules);
    }

    public function expoDatesValidate($attribute, $params)
    {
        $startDate = strtotime($this->startDate);
        $endDate = strtotime($this->endDate);
        if ($endDate < $startDate) {
            $this->addError('endDate', "Дата окончания не может быть раньше даты начала выставки.");
        }
    }

    public function attributeLabels($attributeLabels = [])
    {
        $attributeLabels = [
            'showDates' => 'Выводить календарь',
            'startDate' => 'Начало выставки',
            'endDate' => 'Окончание выставки',
            'city' => 'Город',
        ];
        return parent::attributeLabels($attributeLabels);
    }
}