<?php

namespace core\forms\manage\Expo;


use core\components\SettingsManager;
use core\forms\manage\CommonSettingsForm;

class ExpoSettingsMainForm extends CommonSettingsForm
{
    public $expoName;
    public $expoNameUP;
    public $expoPageSize;
    public $expoSmallSize;
    public $expoBigSize;
    public $expoMaxSize;
    public $expoModeration;

    public function rules()
    {
        return [
            [[SettingsManager::EXPO_NAME, SettingsManager::EXPO_NAME_UP], 'string'],
            [[
                SettingsManager::EXPO_PAGE_SIZE,
                SettingsManager::EXPO_SMALL_SIZE,
                SettingsManager::EXPO_BIG_SIZE,
                SettingsManager::EXPO_MAX_SIZE,
            ], 'integer'],
            [SettingsManager::EXPO_MODERATION, 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            SettingsManager::EXPO_NAME => 'Название',
            SettingsManager::EXPO_NAME_UP => 'Название ППУ',
            SettingsManager::EXPO_PAGE_SIZE => 'Позиций в листинге',
            SettingsManager::EXPO_SMALL_SIZE => 'Размер малой копии',
            SettingsManager::EXPO_BIG_SIZE => 'Размер большой копии',
            SettingsManager::EXPO_MAX_SIZE => 'Максимальный размер',
            SettingsManager::EXPO_MODERATION => 'Модерация обьявлений',
        ];
    }

}