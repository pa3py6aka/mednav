<?php

namespace frontend\widgets;


use core\entities\Board\BoardCategory;
use core\entities\Trade\TradeCategory;
use yii\base\InvalidArgumentException;
use yii\base\Model;
use yii\base\Widget;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class SelectCategoryWidget extends Widget
{
    /* @var $entity BoardCategory|TradeCategory */
    public $entity;

    /* @var $form ActiveForm */
    public $form;

    /* @var $model Model */
    public $model;

    public function init()
    {
        parent::init();
        if (!$this->entity) {
            throw new InvalidArgumentException("Неверное значение `entity`");
        }
        if (!$this->model) {
            throw new InvalidArgumentException("Неверное значение `model`");
        }
        if (!$this->form) {
            throw new InvalidArgumentException("Неверное значение `form`");
        }
    }

    public function run()
    {
        $this->view->registerJs($this->getJs());
        return Html::tag('div', $this->getCategoryDropdowns(), [
            'id' => 'category-block',
            'class' => 'has-overlay',
        ]);
    }

    public function getCategoryDropdowns(): string
    {
        $dropdowns = [];
        $n = 0;
        foreach ($this->model->categoryId as $n => $categoryId) {
            if ($n == 0) {
                $categories = ArrayHelper::map($this->entity::find()->enabled()->roots()->asArray()->all(), 'id', 'name');
            } else if ($n == 2) {
                $categories = ArrayHelper::map($this->entity::findOne($this->model->categoryId[$n - 1])->getDescendants()->active()->enabled()->all(), 'id', function ($item) {
                    return ($item['depth'] > 2 ? str_repeat('-', $item['depth'] - 2) . ' ' : '') . $item['name'];
                });
                $this->model->categoryId[$n] = end($this->model->categoryId);
            } else if ($n == 1) {
                $categories = ArrayHelper::map($this->entity::findOne($this->model->categoryId[$n - 1])->getChildren()->active()->enabled()->all(), 'id', 'name');
            }

            if ($n < 3) {
                if ($n == 0) {
                    $dropdowns[] = $this->form->field($this->model, 'categoryId[' . $n . ']')
                        ->dropDownList($categories, ['prompt' => 'Выберите раздел']);
                } else {
                    $dropdown = Html::activeDropDownList($this->model, 'categoryId[' . $n . ']',$categories, ['class' => 'form-control', 'prompt' => '']);
                    $dropdowns[] = Html::tag('div', $dropdown, ['class' => 'form-group category-dropdown']);
                }
            }
        }

        if ($n < 2 && $this->model->categoryId[$n]) {
            $category = $this->entity::findOne($this->model->categoryId[$n]);
            if ($category->depth == 2) {
                $children = ArrayHelper::map($category->getDescendants()->active()->enabled()->all(), 'id', function ($item) {
                    return ($item['depth'] > 3 ? str_repeat('-', $item['depth'] - 3) . ' ' : '') . $item['name'];
                });
            } else {
                $children = ArrayHelper::map($category->getChildren()->active()->enabled()->all(), 'id', 'name');
            }

            if ($children) {
                $dropdown = Html::activeDropDownList($this->model, 'categoryId[' . ($n + 1) . ']', $children, ['class' => 'form-control', 'prompt' => '']);
                $dropdowns[] = Html::tag('div', $dropdown, ['class' => 'form-group category-dropdown']);
            }
        }
        return implode("\n", $dropdowns);
    }

    public function getJs()
    {
        $url = Url::to(['select-category']);
        return <<<JS
var formName = '{$this->model->formName()}';
$(document).on('change', 'select[name*=categoryId]', function () {
    var select = $(this);
    var box = $(select.parents('.box')[0]);
    select.parent().nextAll('.form-group').remove();
    var id = select.val();
    var emptySelect = false;
    if (!id) {
        var prev = select.parent().prev('.form-group').find('select');
        if (prev.length) {
            id = prev.val();
            emptySelect = true;
        } else {
            $('#parameters-block').html('');
            return;
        }
    }
    $.ajax({
        url: '{$url}',
        method: "post",
        dataType: "json",
        data: {id: id, formName: formName},
        beforeSend: function () {
            box.prepend('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
        },
        success: function(data, textStatus, jqXHR) {
            if (!emptySelect && data.items.length) {
                var dropdownBlock = $('#templates').find('.category-dropdown').clone();
                var dropdown = dropdownBlock.find('select');
                var num = $('#category-block').find('.form-group').length;
                dropdown.attr('name', formName + '[categoryId][' + num + ']');
                $.each(data.items, function (k, item) {
                    dropdown.append('<option value="' + item.id + '">' + item.name + '</option>');
                });
                select.parent().after(dropdownBlock);
            }
            refreshParameters(data.params);
        },
        complete: function () {
            box.find('.overlay').remove();
         }
    });
});

function refreshParameters(params) {
    var paramsBlock = $('#parameters-block');
    paramsBlock.html(params);
}
JS;
    }
}