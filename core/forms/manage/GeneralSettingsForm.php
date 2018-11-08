<?php

namespace core\forms\manage;


use core\components\Settings;

class GeneralSettingsForm extends CommonSettingsForm
{
    public $generalTitle;
    public $generalDescription;
    public $generalKeywords;
    public $generalEmail;
    public $generalEmailFrom;
    public $generalModalsShowtime;

    public function rules()
    {
        return [
            [Settings::GENERAL_MODALS_SHOWTIME, 'integer'],
            [[
                Settings::GENERAL_TITLE,
                Settings::GENERAL_DESCRIPTION,
                Settings::GENERAL_KEYWORDS,
                Settings::GENERAL_EMAIL,
                Settings::GENERAL_EMAIL_FROM,
            ], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            Settings::GENERAL_TITLE => 'Title',
            Settings::GENERAL_DESCRIPTION => 'Description',
            Settings::GENERAL_KEYWORDS => 'Keywords',
            Settings::GENERAL_EMAIL => 'E-mail сайта',
            Settings::GENERAL_EMAIL_FROM => 'От кого',
            Settings::GENERAL_MODALS_SHOWTIME => 'Время показа всплывающих уведомлений(в секундах)',
        ];
    }

}