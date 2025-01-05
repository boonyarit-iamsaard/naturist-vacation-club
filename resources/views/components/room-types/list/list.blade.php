@props([
    'roomTypes' => [],
    'maxDiscount' => 0,
])

<ul class="space-y-6">
    @foreach ($roomTypes as $roomType)
        <x-room-types.list.item
            :name="$roomType->name"
            :description="$roomType->description"
            :weekdayPrice="$roomType->prices_weekday"
            :weekendPrice="$roomType->prices_weekend"
            :maxDiscount="$maxDiscount"
        />
    @endforeach
</ul>
