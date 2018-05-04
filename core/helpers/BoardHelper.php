<?php

namespace core\helpers;


use core\components\SettingsManager;
use core\entities\Board\Board;
use core\entities\Board\BoardCategory;
use core\entities\Board\BoardCategoryRegion;
use core\entities\Board\BoardParameter;
use core\entities\Geo;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

class BoardHelper
{
    public static function generateIndexTitle()
    {

    }

    public static function categoryDescriptionBlock($position, BoardCategory $category = null, BoardCategoryRegion $categoryRegion = null): string
    {
        $constOn = $position == 'top' ? SettingsManager::BOARD_DESCRIPTION_TOP_ON : SettingsManager::BOARD_DESCRIPTION_BOTTOM_ON;
        $const = $position == 'top' ? SettingsManager::BOARD_DESCRIPTION_TOP : SettingsManager::BOARD_DESCRIPTION_BOTTOM;
        $text = '';
        if ($categoryRegion && $categoryRegion->{'description_' . $position . '_on'}) {
            $text = $categoryRegion->{'description_' . $position};
        } else if ($category && $category->{'description_' . $position . '_on'}) {
            $text = $category->{'description_' . $position};
        } else if (Yii::$app->settings->get($const)) {
            $text = Yii::$app->settings->get($const);
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

    public static function breadCrumbs(BoardCategory $category = null, Geo $geo = null)
    {
        $items[] = ['label' => Yii::$app->settings->get(SettingsManager::BOARD_NAME), 'url' => ['/board/board/index']];
        if ($category) {
            foreach ($category->parents as $parent) {
                $items[] = ['label' => $parent->name, 'url' => self::categoryUrl($parent, $geo)];
            }
            $items[] = ['label' => $category->name, 'url' => self::categoryUrl($category, $geo)];
        }

        return Breadcrumbs::widget(['links' => $items]);
    }

    public static function categoryUrl(BoardCategory $category = null, Geo $geo = null)
    {
        return Url::to(['/board/board/index', 'category' => $category ? $category->slug : null, 'region' => $geo ? $geo->slug : 'all']);
    }

    public static function getCountInCategory(BoardCategory $category, $regionId = null): int
    {
        $query = Board::find()
            ->alias('b')
            ->leftJoin(BoardCategory::tableName() . ' c', 'b.category_id=c.id')
            ->where(['c.tree' => $category->tree, 'b.status' => Board::STATUS_ACTIVE])
            ->andWhere(['>=', 'c.lft', $category->lft])
            ->andWhere(['<=', 'c.rgt', $category->rgt]);

        if ($regionId) {
            $query->andWhere(['b.geo_id' => $regionId]);
        }

        return $query->count();
    }

    /**
     * Генерирует поля параметров для формы в зависимости от региона
     * @param BoardCategory $category
     * @param $formName
     * @return string
     */
    public static function generateParameterFields(BoardCategory $category, $formName)
    {
        $parameters = $category->getParametersForForm();
        $html = [];
        foreach ($parameters as $parameter) {
            $parameter = $parameter->parameter;
            if (!$parameter->active) {
                continue;
            }
            if ($parameter->type == BoardParameter::TYPE_DROPDOWN) {
                $label = Html::tag('label', $parameter->name, ['class' => 'control-label']);
                $options = ArrayHelper::map($parameter->boardParameterOptions, 'id', 'name');
                $select = Html::dropDownList($formName . '[params][' . $parameter->id . ']', null, $options, ['class' => 'form-control']);
                $html[] = Html::tag('div', $label . "\n" . $select, ['class' => 'form-group']);
            }

            if ($parameter->type == BoardParameter::TYPE_STRING) {
                $label = Html::tag('label', $parameter->name, ['class' => 'control-label']);
                $input = Html::input('text', $formName . '[params][' . $parameter->id . ']', null, ['class' => 'form-control']);
                $html[] = Html::tag('div', $label . "\n" . $input, ['class' => 'form-group']);
            }

            if ($parameter->type == BoardParameter::TYPE_CHECKBOX) {
                $unchecked = Html::hiddenInput($formName . '[params][' . $parameter->id . ']', 0);
                $checkbox = Html::checkbox($formName . '[params][' . $parameter->id . ']', false, ['label' => $parameter->name]);
                $html[] = Html::tag('div', $unchecked . "\n" . $checkbox, ['class' => 'form-group']);
            }
        }

        return implode("\n", $html);
    }

    public static function statusBadge($status, $name): string
    {
        switch ($status) {
            case Board::STATUS_DELETED:
                $color = 'gray';
                break;
            case Board::STATUS_ON_MODERATION:
                $color = 'red';
                break;
            case Board::STATUS_NOT_ACTIVE:
                $color = 'black';
                break;
            case Board::STATUS_ACTIVE:
                $color = 'green';
                break;
            case Board::STATUS_ARCHIVE:
                $color = 'light-blue';
                break;
            default: $color = 'yellow';
        }

        return Html::tag('span', $name, ['class' => 'badge bg-' . $color]);
    }
}