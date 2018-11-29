<?php

namespace frontend\widgets\PaginationWidget;


use core\helpers\PaginationHelper;
use yii\base\Widget;

class PaginationWidget extends Widget
{
    /* @var \core\entities\Geo|null */
    public $geo;

    /* @var \yii\data\ActiveDataProvider */
    public $provider;

    /* @var \core\entities\Board\BoardCategory|null */
    public $category;

    public function run()
    {
        $type = PaginationHelper::PAGINATION_NUMERIC;
        if ($this->geo) {
            $type = PaginationHelper::PAGINATION_SCROLL;
        } else if ($this->category && $this->category->pagination == PaginationHelper::PAGINATION_NUMERIC) {
            $type = PaginationHelper::PAGINATION_NUMERIC;
        } else if ($this->provider->pagination->pageCount > $this->provider->pagination->page + 1) {
            $type = PaginationHelper::PAGINATION_SCROLL;
        } else {
            return '';
        }

        return $this->render('block', [
            'type' => $type,
            'provider' => $this->provider,
            'geo' => $this->geo,
            'category' => $this->category,
        ]);
    }
}