<?php

namespace core\entities;


interface StatusesInterface
{
    public const STATUS_DELETED = 0;
    public const STATUS_OWNER_USER_DELETED = 1; // Для всех компонентов пользователя при безопасном ужалении юзера
    public const STATUS_ON_PREMODERATION = 5;
    public const STATUS_ACTIVE = 10;
}