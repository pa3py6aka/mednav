<?php

namespace core\helpers;


use core\entities\Board\Board;
use yii\base\UnknownPropertyException;
use yii\bootstrap\Html;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\View;

class MarkHelper
{
    public const MARKS_BOARD = [
        'name' => 'name',
        'geo_name' => 'geo.name',
        'geo_name_p' => 'geo.name_p',
        'phone' => 'author.phoneString',
        'category_name' => 'category.contextName',
        'company_name' => 'author.visibleName',
        'type' => 'defaultType',
    ];
    public const MARKS_TRADE = [
        'name' => 'name',
        'geo_name' => 'geo.name',
        'geo_name_p' => 'geo.name_p',
        'phone' => 'company.phone',
        'category_name' => 'category.contextName',
        'company_name' => 'company.fullName',
    ];


    public static function generateStringByMarks($string, $marks, $entity): string
    {
        foreach ($marks as $mark => $attribute) {
            try {
                /*if (\is_array($attribute)) {
                    foreach ($attribute as $attr) {
                        $replace = ArrayHelper::getValue($entity, $attr, '');
                        if ($replace) {
                            break;
                        }
                    }
                } else {
                    $replace = ArrayHelper::getValue($entity, $attribute, '');
                }*/
                $replace = ArrayHelper::getValue($entity, $attribute, '');
            } catch (UnknownPropertyException $e) {
                $replace = '';
            }

            $string = str_replace('[' . $mark . ']', $replace, $string);
        }
        return $string;
    }

    public static function links(array $marks, $inputId): string
    {
        $links = [];
        foreach ($marks as $mark => $value) {
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