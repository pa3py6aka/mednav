<?php

namespace core\helpers;


use yii\data\Pagination;
use yii\helpers\Html;
use yii\web\JsExpression;

class PaginationHelper
{
    public const PAGINATION_NUMERIC = 1;
    public const PAGINATION_SCROLL = 2;

    public static function paginationTypes(): array
    {
        return [
            self::PAGINATION_NUMERIC => 'Постраничная пагинация',
            self::PAGINATION_SCROLL => 'Вечный скролл',
        ];
    }

    public static function pageSizeSelector(Pagination $pagination, $sizes = []): string
    {
        if (!$sizes) {
            $sizes = [15 => 15, 100 => 100, 500 => 500];
        }
        $html = Html::beginForm($pagination->createUrl(0), 'get');
        $html .= Html::dropDownList('per-page', $pagination->pageSize, $sizes, [
            'class' => 'form-control',
            'style' => 'width:60px;',
            'onchange' => new JsExpression("$(this).parent().find('input[type=hidden][name=per-page]').remove();$(this).parent().submit();"),
        ]);
        $html .= Html::endForm();

        return $html;
    }
}