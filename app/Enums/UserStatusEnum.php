<?php

namespace App\Enums;

enum UserStatusEnum: string
{
    case INVITATION_PENDING = 'invitation_pending';
    case ACTIVE = 'active';
    case DISABLED = 'disabled';
}
