<?php

namespace core\helpers;


use core\components\Settings;
use core\components\SettingsManager;
use core\entities\Board\Board;
use core\entities\Board\BoardCategory;
use core\entities\Board\BoardCategoryRegion;
use core\entities\Board\BoardParameter;
use core\entities\Board\BoardParameterOption;
use core\entities\Geo;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Breadcrumbs;

class BoardHelper
{
    public static function contextCategoryLink(Board $board): string
    {
        $name = $board->category->context_name ?: $board->category->name;
        return Html::a($name, self::categoryUrl($board->category, Yii::$app->session->get('geo', 'all')), ['class' => 'list-lnk']);
    }

    public static function breadCrumbs(BoardCategory $category = null, Geo $geo = null)
    {
        $items[] = ['label' => Yii::$app->settings->get(SettingsManager::BOARD_NAME), 'url' => ['/board/board/list', 'region' => $geo ? $geo->slug : 'all']];
        if ($category) {
            foreach ($category->parents as $parent) {
                if ($parent->isRoot()) {
                    continue;
                }
                $items[] = ['label' => $parent->name, 'url' => self::categoryUrl($parent, $geo)];
            }
            $items[] = ['label' => $category->name, 'url' => self::categoryUrl($category, $geo)];
        }

        return Breadcrumbs::widget(['links' => $items]);
    }

    public static function typeParameterOptions(): array
    {
        return ArrayHelper::map(BoardParameterOption::findAll(['parameter_id' => 1]), 'id', 'name');
    }

    public static function categoryUrl(BoardCategory $category = null, $geo = null, $forFilter = false, $withParams = true)
    {
        if ($withParams) {
            $queryParams = Yii::$app->request->getQueryParams();
            $params = [];
            if (isset($queryParams['sort'])) {
                $params['sort'] = $queryParams['sort'];
            }
            if (!$forFilter) {
                if (isset($queryParams['type'])) {
                    $params['type'] = $queryParams['type'];
                }
            }
        } else {
            $params = [];
        }

        if (!$category && !$geo) {
            $url = ['/board/board/list'];
        } else {
            $geoSlug = $geo && $geo instanceof Geo ? $geo->slug : ($geo ?: 'all');
            $url = ['/board/board/list', 'category' => $category ? $category->slug : null, 'region' => $geoSlug];
        }

        return Url::to(array_merge($url, $params));
    }

    public static function getCountInCategory(BoardCategory $category, $regionId = null): int
    {
        $query = Board::find()
            ->alias('b')
            ->leftJoin(BoardCategory::tableName() . ' c', 'b.category_id=c.id')
            ->active('b', Yii::$app->settings->get(Settings::BOARD_SHOW_ARCHIVE_UNITS))
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
     * @param array $values
     * @return string
     */
    public static function generateParameterFields(BoardCategory $category, $formName, $values = [])
    {
        $parameters = $category->getParametersForForm();
        $html = [];
        foreach ($parameters as $parameter) {
            $parameter = $parameter->parameter;
            if (!$parameter->active) {
                continue;
            }
            $value = isset($values[$parameter->id]) ? $values[$parameter->id] : null;
            if ($parameter->type == BoardParameter::TYPE_DROPDOWN) {
                $label = Html::tag('label', $parameter->name, ['class' => 'control-label']);
                $options = ArrayHelper::map($parameter->boardParameterOptions, 'id', 'name');
                $select = Html::dropDownList($formName . '[params][' . $parameter->id . ']', $value, $options, ['class' => 'form-control']);
                $html[] = Html::tag('div', $label . "\n" . $select, ['class' => 'form-group']);
            }

            if ($parameter->type == BoardParameter::TYPE_STRING) {
                $label = Html::tag('label', $parameter->name, ['class' => 'control-label']);
                $input = Html::input('text', $formName . '[params][' . $parameter->id . ']', $value, ['class' => 'form-control']);
                $html[] = Html::tag('div', $label . "\n" . $input, ['class' => 'form-group']);
            }

            if ($parameter->type == BoardParameter::TYPE_CHECKBOX) {
                $unchecked = Html::hiddenInput($formName . '[params][' . $parameter->id . ']', 0);
                $checkbox = Html::checkbox($formName . '[params][' . $parameter->id . ']', (bool) $value, ['label' => $parameter->name]);
                $html[] = Html::tag('div', $unchecked . "\n" . $checkbox, ['class' => 'form-group']);
            }
        }

        return implode("\n", $html);
    }

    public static function categoryParentsString(BoardCategory $boardCategory): string
    {
        $items = [];
        foreach ($boardCategory->parents as $parent) {
            if ($parent->isRoot()) {
                continue;
            }
            $items[] = $parent->name;
        }
        $items[] = $boardCategory->name;
        return implode(' / ', $items);
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

    public static function getWaitingCountFor($userId = null): int
    {
        $userId = $userId ?: Yii::$app->user->id;
        return Board::find()->where(['author_id' => $userId])->onModeration()->cache(60)->count();
    }
}