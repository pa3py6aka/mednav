<?php

namespace core\grid;


use core\entities\Board\BoardTerm;
use yii\bootstrap\Html;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;

class ModeratorActionColumn extends DataColumn
{
    public $format = 'raw';
    public $label = 'Действия';
    public $contentOptions = ['style' => 'display:flex;'];

    public function renderDataCellContent($model, $key, $index)
    {
        $html = Html::beginForm();
        $html .= Html::hiddenInput('ids[]', $model->id);
        $html .= Html::hiddenInput('action', 'publish');
        $html .= Html::submitButton('Разместить', ['class' => 'btn btn-xs btn-success']);
        $html .= Html::endForm();

        $html .= "&nbsp;";

        $html .= Html::beginForm();
        $html .= Html::hiddenInput('ids[]', $model->id);
        $html .= Html::hiddenInput('action', 'remove');
        $html .= Html::submitButton(Html::tag('i', '', ['class' => 'fa fa-remove']), [
            'class' => 'btn btn-xs btn-danger',
            'data-confirm' => 'Вы уверены?',
        ]);
        $html .= Html::endForm();

        return $html;
    }
}