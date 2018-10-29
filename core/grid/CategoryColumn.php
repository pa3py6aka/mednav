<?php

namespace core\grid;


use core\entities\CategoryAssignmentInterface;
use core\entities\CategoryInterface;
use core\helpers\CategoryHelper;
use yii\grid\DataColumn;
use yii\helpers\Html;

class CategoryColumn extends DataColumn
{
    public $attribute = 'category';
    public $label = 'Раздел';
    public $format = 'raw';
    public $url = false;

    public function renderDataCellContent($model, $key, $index)
    {
        /* @var $category CategoryInterface */
        $category = $model->{$this->attribute};

        if (!$this->url) {
            return Html::encode($category->name);
        }

        if (is_callable($this->url)) {
            $url = call_user_func($this->url, $model);
        } else if (is_array($this->url)) {
            $url = $this->url;
            array_walk($url, function (&$value) use ($category) {
                $value = str_replace('{id}', $category->id, $value);
            });
        } else {
            $url = str_replace('{id}', $category->id, $this->url);
        }

        return Html::a(Html::encode($category->name), $url, [
            'data-toggle' => 'tooltip',
            'title' => CategoryHelper::categoryParentsString($category),
        ]);
    }
}