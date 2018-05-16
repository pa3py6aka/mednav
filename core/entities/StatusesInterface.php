<?php

namespace core\entities;


interface StatusesInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ON_PREMODERATION = 5;
    const STATUS_ACTIVE = 10;
}