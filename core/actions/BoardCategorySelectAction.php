<?php

namespace core\actions;


use core\entities\Board\BoardCategory;
use core\helpers\BoardHelper;
use Yii;
use yii\base\Action;
use yii\helpers\ArrayHelper;

/**
 * Экшин для выбора категорий методом dependency dropdown
 */
class BoardCategorySelectAction extends Action
{
    public function run()
    {
        $id = (int) Yii::$app->request->post('id');
        $formName = Yii::$app->request->post('formName');
        $category = BoardCategory::findOne($id);

        if ($category->depth < 2) {
            $items = $category->getChildren()->orderBy('lft')->active()->enabled()->select(['id', 'name'])->asArray()->all();
        } else if ($category->depth == 2) {
            $items = $category->getDescendants()->orderBy('lft')->select(['id', 'name', 'depth'])->asArray()->active()->enabled()->all();
            array_walk($items, function (&$item, $key) {
                $item = [
                    'id' => $item['id'],
                    'name' => ($item['depth'] > 3 ? str_repeat('-', $item['depth'] - 3) . ' ' : '') . $item['name']
                ];
            });
        } else {
            $items = [];
        }

        return $this->controller->asJson([
            'items' => $items,
            'params' => BoardHelper::generateParameterFields($category, $formName),
        ]);
    }
}