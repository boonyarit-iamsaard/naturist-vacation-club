<?php

namespace App\Enums;

enum MembershipPriceStatus: string
{
    case Active = 'active';
    case Expired = 'expired';
    case Future = 'future';
}
