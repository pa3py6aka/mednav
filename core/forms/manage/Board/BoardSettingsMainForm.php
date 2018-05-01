<?php

namespace core\forms\manage\Board;


use core\components\SettingsManager;
use core\forms\manage\CommonSettingsForm;

class BoardSettingsMainForm extends CommonSettingsForm
{
    public $boardName;
    public $boardNameUP;
    public $boardPageSize;
    public $boardSmallSize;
    public $boardBigSize;
    public $boardMaxSize;
    public $boardModeration;

    public function rules()
    {
        return [
            [[SettingsManager::BOARD_NAME, SettingsManager::BOARD_NAME_UP], 'string'],
            [[
                SettingsManager::BOARD_PAGE_SIZE,
                SettingsManager::BOARD_SMALL_SIZE,
                SettingsManager::BOARD_BIG_SIZE,
                SettingsManager::BOARD_MAX_SIZE,
            ], 'integer'],
            [SettingsManager::BOARD_MODERATION, 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            SettingsManager::BOARD_NAME => 'Название',
            SettingsManager::BOARD_NAME_UP => 'Название ППУ',
            SettingsManager::BOARD_PAGE_SIZE => 'Позиций в листинге',
            SettingsManager::BOARD_SMALL_SIZE => 'Размер малой копии',
            SettingsManager::BOARD_BIG_SIZE => 'Размер большой копии',
            SettingsManager::BOARD_MAX_SIZE => 'Максимальный размер',
            SettingsManager::BOARD_MODERATION => 'Модерация обьявлений',
        ];
    }

}