<?php

namespace core\entities;


use core\entities\Board\Board;
use core\entities\Trade\TradeUserCategory;
use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * @property int $id
 * @property int $name
 * @property string $sign
 * @property int $default
 * @property int $module [smallint]
 */
class Currency extends ActiveRecord
{
    public const MODULE_BOARD = 1;
    public const MODULE_TRADE = 2;

    public static function modulesArray()
    {
        return [
            self::MODULE_BOARD => 'board',
            self::MODULE_TRADE => 'trade',
        ];
    }

    public static function getModuleName($module): ?string
    {
        return ArrayHelper::getValue(self::modulesArray(), $module);
    }

    public static function tableName()
    {
        return '{{%currencies}}';
    }

    public static function getAllFor($module)
    {
        return self::find()->where(['module' => $module])->all();
    }

    public static function getDefaultIdFor($module): int
    {
        return self::find()->where(['default' => 1, 'module' => $module])->one()->id;
    }

    /**
     * @return string|Board|TradeUserCategory
     */
    public function getModuleClass()
    {
        switch ($this->module) {
            case self::MODULE_BOARD:
                return Board::class;
            case self::MODULE_TRADE:
                return TradeUserCategory::class;
        }
        throw new Exception("Неверное значение модуля для денежной единицы.");
    }
}