<?php

namespace App\Services\RoomType;

use App\Enums\PriceStatus;
use App\Models\RoomPrice;

class RoomPriceService
{
    public function getPriceStatus(RoomPrice $price): PriceStatus
    {
        $now = now();

        if ($price->effective_to && $price->effective_to->lte($now)) {
            return PriceStatus::Expired;
        }

        if ($price->effective_from->gt($now)) {
            return PriceStatus::Future;
        }

        return PriceStatus::Active;
    }
}
