<?php

namespace core\entities\Board;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "board_parameter_options".
 *
 * @property int $id
 * @property int $parameter_id
 * @property string $name
 * @property string $slug
 *
 * @property BoardParameter $parameter
 */
class BoardParameterOption extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%board_parameter_options}}';
    }

    public function rules(): array
    {
        return [
            [['parameter_id', 'name', 'slug'], 'required'],
            [['parameter_id'], 'integer'],
            [['name', 'slug'], 'string', 'max' => 255],
            [['parameter_id'], 'exist', 'skipOnError' => false, 'targetClass' => BoardParameter::class, 'targetAttribute' => ['parameter_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'parameter_id' => 'Parameter ID',
            'name' => 'Название',
            'slug' => 'Slug',
        ];
    }

    public function getParameter(): ActiveQuery
    {
        return $this->hasOne(BoardParameter::class, ['id' => 'parameter_id']);
    }
}
