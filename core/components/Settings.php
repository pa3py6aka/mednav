<?php

namespace core\components;


class Settings
{
    public const USER_EMAIL_ACTIVATION = 'userEmailActivation';
    public const USER_PREMODERATION = 'userPremoderation';

    protected $default = [
        self::USER_EMAIL_ACTIVATION => 1,
        self::USER_PREMODERATION => 0,
    ];
}