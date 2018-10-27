<?php

namespace backend\widgets\UserFieldWidget\dependencies;


use backend\widgets\UserFieldWidget\UserIdFieldWidget;

class UserCategoryDependency
{
    public const CATEGORY_SELECTOR_ID = 'category-selector';

    public function getJs()
    {
        $inputId = UserIdFieldWidget::INPUT_ID;
        $selectorId = self::CATEGORY_SELECTOR_ID;
        $js = <<<JS
var id = $('#{$inputId}').val();
$.ajax({
    url: '/trade/category/get-user-categories',
    method: "post",
    dataType: "json",
    data: {id: id},
    success: function (data, textStatus, jqXHR) {
        if (data.result === 'success') {
            var selector = $('#{$selectorId}');
            selector.html('');
            $.each(data.categories, function(id, name) {
                selector.append('<option value="' + id + '">' + name + '</option>');
            });
        } else {
            alert("Произошла какая-то ошибка.");
        }
    }
});
JS;
        return $js;
    }
}