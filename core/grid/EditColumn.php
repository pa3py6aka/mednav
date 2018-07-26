<?php

namespace core\grid;


use core\entities\Board\BoardTerm;
use yii\bootstrap\Html;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class EditColumn extends DataColumn
{
    public $format = 'raw';
    public $contentOptions = ['class' => 'edit-cell has-overlay'];
    public $url;

    public function init()
    {
        parent::init();
        $this->url = $this->url ?: Url::to(['/site/edit-column']);
        $this->grid->getView()->registerJs($this->getJs());
        $this->grid->getView()->registerCss($this->getCss());
    }

    public function renderDataCell($model, $key, $index)
    {
        $this->contentOptions['data-value'] = $model->{$this->attribute} / 100;
        $this->contentOptions['data-id'] = $key;
        return parent::renderDataCell($model, $key, $index);
    }

    public function renderDataCellContent($model, $key, $index)
    {
        return Html::tag('span', $this->getDataCellValue($model, $key, $index));
    }

    public function getJs()
    {
        return <<<JS
$(document).on('click', '.edit-cell', function(e) {
    var cell = $(this);
    if (!cell.hasClass('under-editing')) {
        var value = cell.attr('data-value'),
            input = '<div class="input-group">' +
                        '<input type="number" class="form-control edit-cell-input" value="' + value + '">' +
                        '<span class="input-group-btn">' +
                            '<button class="btn btn-primary edit-cell-save-btn" type="button">' +
                                '<span class="glyphicon glyphicon-ok"></span>' +
                            '</button>' +
                        '</span>' +
                     '</div>';
        cell.html(input)
            .addClass('under-editing');
    }
}).on('click', '.edit-cell-save-btn', function(e) {
    var cell = $(this).closest('.edit-cell'),
        id = cell.attr('data-id'),
        value = cell.find('.edit-cell-input').val();
    $.ajax({
        url: '{$this->url}',
        method: "post",
        dataType: "json",
        data: {id:id, value:value},
        beforeSend: function () {
            cell.prepend(Mednav.overlay);
        },
        success: function(data, textStatus, jqXHR) {
            if (data.result === 'success') {
                cell.removeClass('under-editing')
                    .attr('data-value', data.original)
                    .html('<span>' + data.value + '</span>');
            } else {
                alert(data.message);
            }
        },
        error: function() { alert("Что-то пошло не так..."); },
        complete: function () {
            cell.find('.overlay').remove();
        }
    });
});

JS;
    }

    public function getCss()
    {
        return <<<CSS
.edit-cell > span {border-bottom:1px dashed #454880;cursor:pointer;}
CSS;
    }
}