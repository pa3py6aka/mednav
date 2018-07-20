<?php

namespace core\helpers;


use Yii;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\JsExpression;
use yii\web\Response;

class PaginationHelper
{
    public const PAGINATION_NUMERIC = 1;
    public const PAGINATION_SCROLL = 2;

    public const ADMIN_SIZES = [15 => 15, 100 => 100, 500 => 500];
    public const SITE_SIZES = [25 => 25, 100 => 100, 250 => 250];

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

    public static function getShowMore(Controller $controller, DataProviderInterface $provider, $view, array $params): ?Response
    {
        if (Yii::$app->request->get('showMore')) {
            return $controller->asJson([
                'result' => 'success',
                'html' => $controller->renderPartial($view, $params),
                'nextPageUrl' => $provider->getPagination()->pageCount > $provider->getPagination()->page + 1
                    ? $provider->getPagination()->createUrl($provider->getPagination()->page + 1)
                    : false
            ]);
        }
        return null;
    }
}