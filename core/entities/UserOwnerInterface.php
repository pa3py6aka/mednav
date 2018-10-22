<?php

namespace core\entities;


use core\entities\User\User;

interface UserOwnerInterface
{
    public function getOwnerId(): int;

    public function getOwnerUser(): User;
}