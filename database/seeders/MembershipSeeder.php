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

        $dateFormat = 'Y-m-d';

        foreach ($memberships as $membership) {
            /**
             * Create the membership
             */
            $membershipModel = Membership::create([
                'name' => ucfirst($membership['name']),
                'code' => $membership['code'],
                'room_discount' => $membership['room_discount'],
            ]);

            /**
             * This is the previous standard price for the membership
             *
             * TODO: add a condition to run only in development environment
             */
            MembershipPrice::create([
                'membership_id' => $membershipModel->id,
                'female' => $membership['price']['female'],
                'male' => $membership['price']['male'],
                'type' => 'standard',
                'effective_from' => now()->subMonth()->format($dateFormat),
                'membership_name' => $membershipModel->name,
                'membership_code' => $membershipModel->code,
            ]);

            /**
             * This is the current standard price for the membership
             */
            MembershipPrice::create([
                'membership_id' => $membershipModel->id,
                'female' => $membership['price']['female'],
                'male' => $membership['price']['male'],
                'type' => 'standard',
                'effective_from' => now()->format($dateFormat),
                'membership_name' => $membershipModel->name,
                'membership_code' => $membershipModel->code,
            ]);

            /**
             * This is the expired promotion price for the membership
             *
             * TODO: add a condition to run only in development environment
             */
            MembershipPrice::create([
                'membership_id' => $membershipModel->id,
                'female' => $membership['price']['female'] * 0.9,
                'male' => $membership['price']['male'] * 0.9,
                'type' => 'promotion',
                'promotion_name' => 'Promotion 1',
                'effective_from' => now()->subMonth()->subWeeks(2)->format($dateFormat),
                'effective_to' => now()->subMonth()->format($dateFormat),
                'membership_name' => $membershipModel->name,
                'membership_code' => $membershipModel->code,
            ]);

            /**
             * This is the future promotion price for the membership
             *
             * TODO: add a condition to run only in development environment
             */
            MembershipPrice::create([
                'membership_id' => $membershipModel->id,
                'female' => $membership['price']['female'] * 0.9,
                'male' => $membership['price']['male'] * 0.9,
                'type' => 'promotion',
                'promotion_name' => 'Promotion 2',
                'effective_from' => now()->addMonth()->format($dateFormat),
                'effective_to' => now()->addMonth()->addWeeks(2)->format($dateFormat),
                'membership_name' => $membershipModel->name,
                'membership_code' => $membershipModel->code,
            ]);
        }

        $this->command->info('Memberships seeded.');
    }
}
