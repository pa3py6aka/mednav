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
        $this->registerJs();
        return $this->render('categories-select-modal', [
            'categories' => $this->categoriesList(),
        ]);
    }

    public function categoriesList()
    {
        $categories = ArrayHelper::map(CompanyCategory::find()->where(['>', 'depth', 0])->orderBy('lft')->asArray()->all(), 'id', function (array $category) {
            $options = $category['depth'] > 1 ? ['style' => 'margin-left: ' . ($category['depth'] * 10) . 'px;'] : [];
            return [
                'depth' => $category['depth'],
                'checkbox' => Html::tag('div', Html::activeCheckbox($this->model, 'categories[' . $category['id'] . ']', ['label' => $category['name'], 'value' => $category['id']]), $options),
            ];
        });

        return $categories;
    }

    public function registerJs()
    {
        $js = <<<JS
$('#caterigoriesSelectModalSubmit').click(function() {
  var counts = $('input[name*=categories]:checked').length,
      val = counts > 0 ? '1' : '';
  $('#compCategory0').val(val);
  $('#caterigoriesSelectModal').modal('hide');
  //$('#company-form').yiiActiveForm('validate', true);
  $('[data-target*=caterigoriesSelectModal]').text(counts > 0 ? Mednav.public.pluralize(counts, ['Выбран', 'Выбрано', 'Выбрано']) + ' ' + counts + ' ' + Mednav.public.pluralize(counts, ['раздел', 'раздела', 'разделов']) : 'Выбрать');
});
JS;
        $this->view->registerJs($js);
    }
}