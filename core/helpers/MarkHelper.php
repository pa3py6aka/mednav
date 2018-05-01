<?php

namespace core\helpers;


use yii\bootstrap\Html;
use yii\web\View;

class MarkHelper
{
    public const MARKS_BOARD = [
        'name' => 'name',
        'geo_name' => 'geo_name',
        'geo_name_p' => 'geo_name_p',
        'phone' => 'phone',
        'category_name' => 'category_name',
        'company_name' => 'company_name',
        'type' => 'type',
    ];

    public static function links(array $marks, $inputId): string
    {
        $links = [];
        foreach ($marks as $mark) {
            $links[] = Html::a($mark, '#');
        }
        return '<div data-block="marks" data-input-id="' . $inputId . '">' . implode(' | ', $links) . '</div>';
    }

    public static function js(View $view)
    {
        $js = <<<JS
    //$(document).ready(function() {
        $("[data-block=marks]").on('click', 'a', function() {
            var input = $('#' + $(this).parent().attr('data-input-id'));
            var mark = input.val()+'['+$(this).text()+'], ';
            input.val(mark);
            return false;
        });
    //});
JS;
        $view->registerJs($js);
    }
}