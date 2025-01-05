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
     * @property-read int $prices_weekday
     * @property-read int $prices_weekend
     */
    private function getRoomTypes(): Collection
    {
        return RoomType::query()
            ->with(['rooms'])
            ->withAggregate(['prices' => fn ($query) => $query->standard()->active()], 'weekday')
            ->withAggregate(['prices' => fn ($query) => $query->standard()->active()], 'weekend')
            ->orderByDesc('prices_weekday')
            ->get();
    }
}
