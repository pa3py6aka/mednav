<?php

namespace core\entities\Board;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%board_parameters}}".
 *
 * @property int $id
 * @property string $name
 * @property int $type
 * @property int $active
 * @property int $sort
 *
 * @property string $typeName
 *
 * @property BoardCategoryParameter[] $boardCategoryParameters
 * @property BoardCategory[] $categories
 * @property BoardParameterOption[] $boardParameterOptions
 */
class BoardParameter extends ActiveRecord
{
    public const TYPE_DROPDOWN = 1;
    public const TYPE_STRING = 2;
    public const TYPE_CHECKBOX = 3;

    public static function create($name, $type, $active): BoardParameter
    {
        $parameter = new static();
        $parameter->name = $name;
        $parameter->type = $type;
        $parameter->active = $active;
        return $parameter;
    }

    public function edit($name, $type, $active): void
    {
        $this->name = $name;
        $this->type = $type;
        $this->active = $active;
    }

    public static function typesArray(): array
    {
        return [
            self::TYPE_DROPDOWN => 'Выпадающий список',
            self::TYPE_STRING => 'Текстовое поле',
            self::TYPE_CHECKBOX => 'Checkbox',
        ];
    }

    public function getTypeName(): string
    {
        return ArrayHelper::getValue(self::typesArray(), $this->type, '-');
    }

    public static function tableName(): string
    {
        return '{{%board_parameters}}';
    }

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['sort'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 3],
            [['active'], 'string', 'max' => 1],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'type' => 'Тип',
            'active' => 'Показывать',
            'sort' => 'Сортировка',
        ];
    }

    public function getBoardCategoryParameters(): ActiveQuery
    {
        return $this->hasMany(BoardCategoryParameter::class, ['parameter_id' => 'id']);
    }

    public function getCategories(): ActiveQuery
    {
        return $this->hasMany(BoardCategory::class, ['id' => 'category_id'])->viaTable('{{%board_category_parameters}}', ['parameter_id' => 'id']);
    }

    public function getBoardParameterOptions(): ActiveQuery
    {
        return $this->hasMany(BoardParameterOption::class, ['parameter_id' => 'id']);
    }
}
