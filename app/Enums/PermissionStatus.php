<?php
namespace App\Enums;

enum PermissionStatus: string
{
    case ENABLED  = 'enabled';
    case DISABLED = 'disabled';
}
