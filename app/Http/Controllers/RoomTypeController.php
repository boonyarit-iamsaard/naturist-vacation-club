<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;

class RoomTypeController extends Controller
{
    public function index(): View
    {
        return view('room-types.index', [
            'maxDiscount' => $this->getMaxDiscount(),
            'roomTypes' => $this->getRoomTypes(),
        ]);
    }

    private function getMaxDiscount(): int
    {
        return Membership::query()
            ->orderByDesc('room_discount')
            ->first()
            ->room_discount ?? 0;
    }

    /**
     * @return Collection<int, RoomType>
     *
     * @property-read string $name
     * @property-read string $code
     * @property-read string $description
     * @property-read int $room_price_weekday
     * @property-read int $room_price_weekend
     */
    private function getRoomTypes(): Collection
    {
        return RoomType::query()
            ->with(['rooms'])
            ->withAggregate('roomPrice', 'weekday')
            ->withAggregate('roomPrice', 'weekend')
            ->orderByDesc('room_price_weekday')
            ->get();
    }
}
