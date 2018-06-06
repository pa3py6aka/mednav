<?php

namespace frontend\widgets;


use core\entities\Company\CompanyCategory;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class CompanyCategoriesSelectWidget extends Widget
{
    public $model;

    public function run()
    {
        return $this->render('categories-select-modal', [
            'categories' => $this->categoriesList(),
        ]);
    }

    public function categoriesList()
    {
        $categories = ArrayHelper::map(CompanyCategory::find()->orderBy('tree, lft')->asArray()->all(), 'id', function (array $category) {
            $options = $category['depth'] > 0 ? ['style' => 'margin-left: ' . ($category['depth'] * 10) . 'px;'] : [];
            return [
                'depth' => $category['depth'],
                'checkbox' => Html::activeCheckbox($this->model, 'categories[' . $category['id'] . ']', $options),
            ];
        });

        return $categories;
    }
}