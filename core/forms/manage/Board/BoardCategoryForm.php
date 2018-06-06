<?php

namespace core\forms\manage\Board;


use core\entities\Board\BoardCategory;
use core\forms\manage\CategoryForm;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

class BoardCategoryForm extends CategoryForm
{
    public $parameters;

    public function __construct(BoardCategory $category = null, $config = [])
    {
        if ($category) {
            $this->parameters = $category->getParameters()->select('parameter_id')->column();
        }
        parent::__construct(BoardCategory::class, $category, $config);
    }

    public function rules()
    {

        return array_merge(parent::rules(), [
            ['parameters', 'each', 'rule' => ['integer']],
        ]);
    }

    public function beforeValidate()
    {
        $this->parameters = $this->parameters ?: [];
        return parent::beforeValidate();
    }

    public function attributeLabels()
    {
        return array_merge([
            'parameters' => 'Параметры',
        ], parent::attributeLabels());
    }
}