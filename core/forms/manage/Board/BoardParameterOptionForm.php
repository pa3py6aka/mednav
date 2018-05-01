<?php

namespace core\forms\manage\Board;


use core\entities\Board\BoardParameterOption;
use yii\base\Model;
use yii\helpers\Inflector;

class BoardParameterOptionForm extends Model
{
    public $name;
    public $slug;

    private $_option;

    public function __construct(BoardParameterOption $option = null, array $config = [])
    {
        if ($option) {
            $this->name = $option->name;
            $this->slug = $option->slug;

            $this->_option = $option;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name', 'slug'], 'string'],
            ['slug', 'unique', 'targetClass' => BoardParameterOption::class, 'filter' => $this->_option ? ['<>', 'id', $this->_option->id] : null],
        ];
    }

    public function beforeValidate()
    {
        $this->slug = $this->slug ? Inflector::slug($this->slug) : Inflector::slug($this->name);
        return parent::beforeValidate();
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Название',
            'slug' => 'Slug',
        ];
    }
}