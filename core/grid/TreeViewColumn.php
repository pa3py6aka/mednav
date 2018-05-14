<?php

namespace core\grid;


use core\helpers\BoardHelper;
use yii\bootstrap\Html;
use yii\grid\DataColumn;

class TreeViewColumn extends DataColumn
{
    public $entity;

    public $format = 'raw';
    public $contentOptions = ['class' => 'tree-cell'];

    public function renderDataCellContent($model, $key, $index)
    {
        return self::cellContent($model, $this->entity);
    }

    public static function cellContent($model, $entity)
    {
        $childrenCount = $model->getChildren()->count();

        $adCount = BoardHelper::getCountInCategory($model);

        $indent = ($model->depth > 0 ? str_repeat('&nbsp; &nbsp; &nbsp; &nbsp;', $model->depth - 0) . ' ' : '');

        $arrow = $childrenCount ? Html::tag('span', '', ['class' => 'tree-arrow glyphicon glyphicon-triangle-right', 'data-id' => $model->id, 'data-entity' => $entity]) . ' ' : '';

        $icon = Html::tag('span', '', ['class' => 'fa fa-' . ($childrenCount ? 'folder' : 'file-o')]) . ' ';

        $counts = $adCount ? ' (' . $adCount . ')' : '';

        $string = $indent . $arrow . $icon . Html::a(Html::encode($model->name), ['/board/category/update', 'id' => $model->id]) . $counts;

        return Html::tag('div', $string);
    }
}