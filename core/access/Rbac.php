<?php

namespace core\access;


class Rbac
{
    public const ROLE_ADMIN = 'admin';
    public const ROLE_MODERATOR  = 'moderator';
    public const ROLE_USER = 'user';

    public const PERMISSION_MANAGE = 'manage';
    public const PERMISSION_OWN_MANAGE = 'ownManage';
}