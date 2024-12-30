<?php

namespace Database\Seeders;

use App\Models\Membership;
use App\Models\MembershipPrice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class MembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Start seeding memberships...');

        $memberships = json_decode(Storage::get('json/memberships.json'), true);

        if (is_null($memberships)) {
            $this->command->warn('Memberships seed data not found, skipping...');

            return;
        }

        $this->command->info('Seeding memberships...');

        foreach ($memberships as $membership) {
            $price = MembershipPrice::create([
                'female' => $membership['price']['female'],
                'male' => $membership['price']['male'],
            ]);

            Membership::create([
                'name' => $membership['name'],
                'code' => $membership['code'],
                'room_discount' => $membership['room_discount'],
                'membership_price_id' => $price->id,
            ]);
        }

        $this->command->info('Memberships seeded.');
    }
}
