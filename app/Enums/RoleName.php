<?php

namespace App\Enums;

enum RoleName: string
{
    case ADMIN      = 'admin';
    case MODERATOR  = 'moderator';
    case USER       = 'user';
}
