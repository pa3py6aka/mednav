<?php

namespace core\helpers;


use core\entities\Board\BoardCategory;
use core\entities\Board\BoardCategoryRegion;
use core\entities\Company\CompanyCategory;
use core\entities\Company\CompanyCategoryRegion;
use Yii;
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

    /**
     * @param string $titleParam (например SettingsManager::BOARD_TITLE)
     * @param BoardCategory|CompanyCategory|null $category
     * @param BoardCategoryRegion|CompanyCategoryRegion|null $categoryRegion
     * @return string
     */
    public static function getTitleForList($titleParam, $category = null, $categoryRegion = null): string
    {
        $title = $categoryRegion ? $categoryRegion->title : '';
        $title = $title ?: ($category ? $category->title : '');
        $title = $title ?: ($category ? $category->name : '');
        $title = !$category && !$categoryRegion ? Yii::$app->settings->get($titleParam) : $title;
        return $title;
    }

    public static function categoryDescriptionBlock($position, $settingsParam, $isMainPage, $category = null, $categoryRegion = null): string
    {
        $text = '';
        if ($categoryRegion && ($isMainPage || !$categoryRegion->{'description_' . $position . '_on'})) {
            $text = $categoryRegion->{'description_' . $position};
        } else if ($category && ($isMainPage || !$category->{'description_' . $position . '_on'})) {
            $text = $category->{'description_' . $position};
        } else if (Yii::$app->settings->get($settingsParam)) {
            $text = Yii::$app->settings->get($settingsParam);
        }

        if ($text) {
            return '<div class="row">' .
                '<div class="col-md-12 col-sm-12 hidden-xs"><div class="list-category-desc">' .
                $text .
                '</div></div>' .
                '</div>';
        }

        return '';
    }

}