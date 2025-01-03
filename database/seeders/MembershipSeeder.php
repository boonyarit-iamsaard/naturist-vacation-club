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

        if ($memberships === null) {
            $this->command->warn('Memberships seed data not found, skipping...');

            return;
        }

        $this->command->info('Seeding memberships...');

        foreach ($memberships as $membership) {
            // Create membership first
            $membershipModel = Membership::create([
                'name' => ucfirst($membership['name']),
                'code' => $membership['code'],
                'room_discount' => $membership['room_discount'],
            ]);

            // Then create standard price for the membership
            MembershipPrice::create([
                'membership_id' => $membershipModel->id,
                'female' => $membership['price']['female'],
                'male' => $membership['price']['male'],
                'type' => 'standard',
                'effective_from' => now(),
            ]);
        }

        $this->command->info('Memberships seeded.');
    }
}
