<?php

namespace core\helpers;


use yii\helpers\Html;
use yii\web\JqueryAsset;

class HtmlHelper
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

    public static function actionButtonForSelected($content, $action, $color)
    {
        \Yii::$app->view->registerJsFile(\Yii::$app->params['frontendHostInfo'] . '/js/action-for-selected-rows.js', ['depends' => [JqueryAsset::class]], 'action-for-selected-rows');
        return Html::button($content, ['class' => 'action-btn btn btn-flat btn-' . $color, 'data-action' => $action]);
    }

    public static function tabStatus($tab, $current)
    {
        return $current == $tab ? ' class="active"' : '';
    }

    public static function active($one, $two)
    {
        return $one == $two ? ' active' : '';
    }
}