<?php

namespace core\entities\Board;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "board_category_parameters".
 *
 * @property int $category_id
 * @property int $parameter_id
 *
 * @property BoardCategory $category
 * @property BoardParameter $parameter
 */
class BoardCategoryParameter extends ActiveRecord
{
    public static function create($categoryId, $parameterId): BoardCategoryParameter
    {
        $assignment = new static();
        $assignment->category_id = $categoryId;
        $assignment->parameter_id = $parameterId;
        return $assignment;
    }

    public static function tableName(): string
    {
        return '{{%board_category_parameters}}';
    }

    public function rules(): array
    {
        return [
            [['category_id', 'parameter_id'], 'required'],
            [['category_id', 'parameter_id'], 'integer'],
            [['category_id', 'parameter_id'], 'unique', 'targetAttribute' => ['category_id', 'parameter_id']],
            [['category_id'], 'exist', 'skipOnError' => false, 'targetClass' => BoardCategory::class, 'targetAttribute' => ['category_id' => 'id']],
            [['parameter_id'], 'exist', 'skipOnError' => false, 'targetClass' => BoardParameter::class, 'targetAttribute' => ['parameter_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'category_id' => 'Category ID',
            'parameter_id' => 'Parameter ID',
        ];
    }

    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(BoardCategory::class, ['id' => 'category_id']);
    }

    public function getParameter(): ActiveQuery
    {
        return $this->hasOne(BoardParameter::class, ['id' => 'parameter_id']);
    }
}
