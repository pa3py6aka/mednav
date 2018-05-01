<?php

namespace core\entities\Board;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%board_parameters}}".
 *
 * @property int $id
 * @property string $name
 * @property int $type
 * @property int $active
 * @property int $sort
 *
 * @property BoardCategoryParameter[] $boardCategoryParameters
 * @property BoardCategory[] $categories
 * @property BoardParameterOption[] $boardParameterOptions
 */
class BoardParameter extends ActiveRecord
{
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
