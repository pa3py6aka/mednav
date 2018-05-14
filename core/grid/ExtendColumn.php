<?php

namespace core\grid;


use core\entities\Board\BoardTerm;
use yii\bootstrap\Html;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;

class ExtendColumn extends DataColumn
{
    public $format = 'raw';
    public $label = 'Продление';
    public $contentOptions = ['class' => 'extend-cell'];

    public function renderDataCellContent($model, $key, $index)
    {
        $terms = ArrayHelper::map(BoardTerm::find()->asArray()->all(), 'id', 'daysHuman');
        $html = Html::beginForm();
        $html .= Html::hiddenInput('ids[]', $model->id);
        $html .= Html::hiddenInput('action', 'extend');
        $html .= Html::dropDownList('term', $model->term_id, $terms, ['class' => 'form-control']);
        $html .= Html::submitButton('Продлить', ['class' => 'btn btn-xs btn-primary']);
        $html .= Html::endForm();

        return $html;
    }
}