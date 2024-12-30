<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Start seeding admin...');

        $admin = Storage::json('json/admin.json');

        if ($admin === null) {
            $this->command->warn('Admin seed data not found, skipping...');

            return;
        }

        $this->command->info('Seeding admin...');

        User::create([
            'name' => $admin['name'],
            'email' => $admin['email'],
            'gender' => $admin['gender'],
            'role' => $admin['role'],
            'email_verified_at' => now(),
            'first_login_at' => now(),
            'password' => Hash::make($admin['password']),
            'remember_token' => Str::random(10),
        ]);

        $this->command->info('Admin seeded.');
    }
}
