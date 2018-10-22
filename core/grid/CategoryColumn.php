<?php

namespace core\grid;


use core\entities\CategoryAssignmentInterface;
use core\helpers\CategoryHelper;
use yii\grid\DataColumn;
use yii\helpers\Html;

class CategoryColumn extends DataColumn
{
    public $attribute = 'category';
    public $label = 'Раздел';
    public $format = 'raw';

    public function renderDataCellContent($model, $key, $index)
    {
        /* @var $model CategoryAssignmentInterface */
        return Html::a($model->category->name, ['/board/category/update', 'id' => $model->category_id], [
            'data-toggle' => 'tooltip',
            'title' => CategoryHelper::categoryParentsString($model->category),
        ]);
    }
}