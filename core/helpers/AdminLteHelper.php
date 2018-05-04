<?php

namespace core\helpers;


use yii\helpers\Html;

class AdminLteHelper
{
    public static function softDeleteButton($id): string
    {
        $softButton = Html::a('Удалить', ['delete', 'id' => $id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => 'Вы уверены?',
                'method' => 'post',
            ],
        ]);
        $hardButton = Html::a('Удалить полностью', ['delete', 'id' => $id, 'hard' => 1], [
            'data' => [
                'confirm' => 'Вы уверены что хотите удалить этот элемент из базы?',
                'method' => 'post',
            ],
        ]);
        $caret = Html::button('<span class="caret"></span><span class="sr-only">Toggle Dropdown</span>', [
            'type' => 'button',
            'class' => 'btn btn-danger btn-flat dropdown-toggle',
            'data-toggle' => 'dropdown',
        ]);
        $ul = Html::ul([$hardButton], ['class' => 'dropdown-menu', 'role' => 'menu', 'encode' => false]);

        return Html::tag('div', $softButton . $caret . $ul, ['class' => 'btn-group']);
    }
}