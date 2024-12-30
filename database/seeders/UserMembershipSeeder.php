<?php

namespace Database\Seeders;

use App\Models\Membership;
use App\Models\User;
use App\Models\UserMembership;
use Illuminate\Database\Seeder;

class UserMembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Start seeding user memberships...');

        $memberships = Membership::all();

        if ($memberships->isEmpty()) {
            $this->command->warn('No memberships found, skipping...');

            return;
        }

        $membershipCount = $memberships->count();
        $users = User::all();
        $totalUsers = $users->count();

        if ($totalUsers < $membershipCount + 1) {
            $this->command->info('Not enough users to seed memberships, skipping...');

            return;
        }

        $usersPerMembership = intdiv($totalUsers, $membershipCount + 1);
        $offset = 0;

        $this->command->info('Seeding user memberships...');

        foreach ($memberships as $membership) {
            $membershipUsers = $users->slice($offset, $usersPerMembership);
            $offset += $usersPerMembership;

            foreach ($membershipUsers as $user) {
                $price = $membership->price[$user->gender] ?? 0;

                if ($price === 0) {
                    continue;
                }

                /**
                 * Create expired membership
                 */
                UserMembership::create([
                    'user_id' => $user->id,
                    'membership_id' => $membership->id,
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'user_gender' => $user->gender,
                    'membership_name' => $membership->name,
                    'membership_price_at_joining' => $price,
                    'start_date' => now()->subYears(2)->addMonths(6),
                ]);

                /**
                 * Create active membership starting right after the expired one ends
                 */
                UserMembership::create([
                    'user_id' => $user->id,
                    'membership_id' => $membership->id,
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'user_gender' => $user->gender,
                    'membership_name' => $membership->name,
                    'membership_price_at_joining' => $price,
                    'start_date' => now()->subYear()->addMonths(6)->addDay(),
                ]);

                $user->update(['role' => 'member']);
            }
        }

        $this->command->info('User memberships seeded.');
    }
}
