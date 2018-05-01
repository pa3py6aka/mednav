<?php

namespace core\forms\manage\Board;


use core\entities\Board\BoardParameter;
use yii\base\Model;

class BoardParameterForm extends Model
{
    public $name;
    public $type;
    public $active;

    public function __construct(BoardParameter $parameter = null, array $config = [])
    {
        if ($parameter) {
            $this->name = $parameter->name;
            $this->type = $parameter->type;
            $this->active = $parameter->active;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name', 'type', 'active'], 'required'],
            ['name', 'string'],
            ['type', 'in', 'range' => array_keys(BoardParameter::typesArray())],
            ['active', 'boolean'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => 'Название',
            'type' => 'Тип',
            'active' => 'Показывать',
        ];
    }
}