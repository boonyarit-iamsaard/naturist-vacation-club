<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold leading-tight">
            Our Rooms
        </h2>
    </x-slot>

    <x-slot name="slot">
        <div class="space-y-12">
            <x-room-types.list
                :roomTypes="$roomTypes"
                :maxDiscount="$maxDiscount"
            />
        </div>
    </x-slot>
</x-app-layout>
