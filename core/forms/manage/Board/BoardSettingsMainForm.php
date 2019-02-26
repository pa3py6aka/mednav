<?php

namespace core\forms\manage\Board;


use core\components\Settings;
use core\entities\Board\Board;
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
    public $boardShowArchiveUnits;
    public $boardShowEmptyCategories;

    public function rules()
    {
        return [
            [[Settings::BOARD_NAME, Settings::BOARD_NAME_UP], 'string'],
            [[
                Settings::BOARD_PAGE_SIZE,
                Settings::BOARD_SMALL_SIZE,
                Settings::BOARD_BIG_SIZE,
                Settings::BOARD_MAX_SIZE,
            ], 'integer'],
            [[Settings::BOARD_MODERATION, Settings::BOARD_SHOW_ARCHIVE_UNITS, Settings::BOARD_SHOW_EMPTY_CATEGORIES], 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            Settings::BOARD_NAME => 'Название',
            Settings::BOARD_NAME_UP => 'Название ППУ',
            Settings::BOARD_PAGE_SIZE => 'Позиций в листинге',
            Settings::BOARD_SMALL_SIZE => 'Размер малой копии',
            Settings::BOARD_BIG_SIZE => 'Размер большой копии',
            Settings::BOARD_MAX_SIZE => 'Максимальный размер',
            Settings::BOARD_MODERATION => 'Модерация обьявлений',
            Settings::BOARD_SHOW_ARCHIVE_UNITS => 'Показывать архивные объявления в листинге',
            Settings::BOARD_SHOW_EMPTY_CATEGORIES => 'Скрывать разделы без объявлений',
        ];
    }

}