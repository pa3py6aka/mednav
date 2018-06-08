<?php

namespace core\entities;


interface UserOwnerInterface
{
    public function getOwnerId(): int;
}