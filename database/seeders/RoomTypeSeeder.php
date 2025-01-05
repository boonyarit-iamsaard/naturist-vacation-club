<?php

namespace Database\Seeders;

use App\Enums\PriceType;
use App\Models\Room;
use App\Models\RoomPrice;
use App\Models\RoomType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Start seeding room types...');

        $roomTypes = json_decode(Storage::get('json/room_types.json'), true);

        if ($roomTypes === null) {
            $this->command->warn('Room types seed data not found, skipping...');

            return;
        }

        $this->command->info('Seeding room types...');

        $dateFormat = 'Y-m-d';

        foreach ($roomTypes as $roomType) {
            $createdRoomType = RoomType::create([
                'name' => $roomType['name'],
                'code' => $roomType['code'],
                'description' => $roomType['description'],
            ]);

            RoomPrice::create([
                'room_type_id' => $createdRoomType->id,
                'weekday' => $roomType['price']['weekday'],
                'weekend' => $roomType['price']['weekend'],
                'type' => PriceType::Standard,
                'effective_from' => now()->format($dateFormat),
                'room_type_name' => $createdRoomType->name,
                'room_type_code' => $createdRoomType->code,
            ]);

            for ($i = 1; $i <= $roomType['quantity']; $i++) {
                Room::create([
                    'name' => "{$createdRoomType->code}{$i}",
                    'room_type_id' => $createdRoomType->id,
                    'room_type_name' => $createdRoomType->name,
                    'room_type_code' => $createdRoomType->code,
                ]);
            }
        }

        $this->command->info('Room types and rooms seeded.');
    }
}
