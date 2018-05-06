<?php

namespace core\entities\Board;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%board_parameters_assignment}}".
 *
 * @property int $board_id
 * @property int $parameter_id
 * @property int $option_id
 * @property string $value
 *
 * @property Board $board
 * @property BoardParameterOption $option
 * @property BoardParameter $parameter
 */
class BoardParameterAssignment extends ActiveRecord
{
    public static function create($boardId, $parameterId, $type, $value): BoardParameterAssignment
    {
        $assignment = new static();
        $assignment->board_id = $boardId;
        $assignment->parameter_id = $parameterId;
        if ($type == BoardParameter::TYPE_DROPDOWN) {
            $assignment->option_id = $value;
            $assignment->value = '';
        } else if ($type == BoardParameter::TYPE_STRING) {
            $assignment->value = $value;
        } else if ($type == BoardParameter::TYPE_CHECKBOX) {
            $assignment->value = $value;
        }
        return $assignment;
    }

    public function getValueByType($forForm = false)
    {
        $type = $this->parameter->type;
        if ($type == BoardParameter::TYPE_DROPDOWN) {
            $value = $forForm ? $this->option_id : $this->option->name;
        } else if ($type == BoardParameter::TYPE_STRING) {
            $value = $this->value;
        } else if ($type == BoardParameter::TYPE_CHECKBOX) {
            $value = $forForm ? $this->value : Yii::$app->formatter->asBoolean($this->value);
        } else {
            $value = null;
        }
        return $value;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%board_parameters_assignment}}';
    }

    /**
     * @inheritdoc

    public function rules()
    {
        return [
            [['board_id', 'parameter_id'], 'required'],
            [['board_id', 'parameter_id', 'option_id'], 'integer'],
            [['value'], 'string', 'max' => 255],
            [['board_id', 'parameter_id'], 'unique', 'targetAttribute' => ['board_id', 'parameter_id']],
            [['board_id'], 'exist', 'skipOnError' => true, 'targetClass' => Boards::className(), 'targetAttribute' => ['board_id' => 'id']],
            [['option_id'], 'exist', 'skipOnError' => true, 'targetClass' => BoardParameterOptions::className(), 'targetAttribute' => ['option_id' => 'id']],
            [['parameter_id'], 'exist', 'skipOnError' => true, 'targetClass' => BoardParameters::className(), 'targetAttribute' => ['parameter_id' => 'id']],
        ];
    }*/

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'board_id' => 'Board ID',
            'parameter_id' => 'Parameter ID',
            'option_id' => 'Option ID',
            'value' => 'Value',
        ];
    }

    public function getBoard(): ActiveQuery
    {
        return $this->hasOne(Board::class, ['id' => 'board_id']);
    }

    public function getOption(): ActiveQuery
    {
        return $this->hasOne(BoardParameterOption::class, ['id' => 'option_id']);
    }

    public function getParameter(): ActiveQuery
    {
        return $this->hasOne(BoardParameter::class, ['id' => 'parameter_id']);
    }
}
