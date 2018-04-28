<?php

namespace core\forms\manage\User;


use core\components\SettingsManager;
use core\forms\manage\CommonSettingsForm;

class UserSettingsForm extends CommonSettingsForm
{
    public $userEmailActivation;
    public $userPremoderation;

    public function rules()
    {
        return [
            [[SettingsManager::USER_EMAIL_ACTIVATION, SettingsManager::USER_PREMODERATION], 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            SettingsManager::USER_EMAIL_ACTIVATION => 'Подтверждение регистрации пользователя через e-mail',
            SettingsManager::USER_PREMODERATION => 'Модерация пользователей',
        ];
    }

}