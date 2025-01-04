<?php

use App\Models\Membership;
use App\Models\User;
use App\Models\UserMembership;
use Database\Seeders\MembershipSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('membership has many user memberships', function () {
    $this->seed(MembershipSeeder::class);
    $membership = Membership::where('code', 'G')->first();

    // Create a user membership for testing
    $user = User::factory()->create(['gender' => 'female']);
    UserMembership::create([
        'user_id' => $user->id,
        'membership_id' => $membership->id,
        'membership_number' => 'G001',
        'user_name' => $user->name,
        'user_email' => $user->email,
        'user_gender' => $user->gender,
        'membership_name' => $membership->name,
        'membership_price_at_joining' => $membership->prices->first()->female,
        'start_date' => now(),
        'end_date' => now()->addYear(),
    ]);

    expect($membership->userMemberships)
        ->toBeCollection()
        ->not->toBeEmpty()
        ->and($membership->userMemberships->first())
        ->toBeInstanceOf(UserMembership::class);
});

test('user membership belongs to membership', function () {
    $this->seed(MembershipSeeder::class);
    $membership = Membership::where('code', 'S')->first();
    $user = User::factory()->create(['gender' => 'female']);

    $userMembership = UserMembership::create([
        'user_id' => $user->id,
        'membership_id' => $membership->id,
        'membership_number' => 'S001',
        'user_name' => $user->name,
        'user_email' => $user->email,
        'user_gender' => $user->gender,
        'membership_name' => $membership->name,
        'membership_price_at_joining' => $membership->prices->first()->female,
        'start_date' => now(),
        'end_date' => now()->addYear(),
    ]);

    expect($userMembership->membership)
        ->toBeInstanceOf(Membership::class)
        ->and($userMembership->membership->name)
        ->not->toBeEmpty();
});

test('user has many user memberships', function () {
    $this->seed(MembershipSeeder::class);
    $user = User::factory()->create(['gender' => 'female']);
    $membership = Membership::where('code', 'S')->first();

    UserMembership::create([
        'user_id' => $user->id,
        'membership_id' => $membership->id,
        'membership_number' => 'S001',
        'user_name' => $user->name,
        'user_email' => $user->email,
        'user_gender' => $user->gender,
        'membership_name' => $membership->name,
        'membership_price_at_joining' => $membership->prices->first()->female,
        'start_date' => now(),
        'end_date' => now()->addYear(),
    ]);

    expect($user->userMemberships)
        ->toBeCollection()
        ->not->toBeEmpty()
        ->and($user->userMemberships->first())
        ->toBeInstanceOf(UserMembership::class);
});

test('user membership belongs to user', function () {
    $this->seed(MembershipSeeder::class);
    $user = User::factory()->create(['gender' => 'female']);
    $membership = Membership::where('code', 'S')->first();

    $userMembership = UserMembership::create([
        'user_id' => $user->id,
        'membership_id' => $membership->id,
        'membership_number' => 'S001',
        'user_name' => $user->name,
        'user_email' => $user->email,
        'user_gender' => $user->gender,
        'membership_name' => $membership->name,
        'membership_price_at_joining' => $membership->prices->first()->female,
        'start_date' => now(),
        'end_date' => now()->addYear(),
    ]);

    expect($userMembership->user)
        ->toBeInstanceOf(User::class)
        ->and($userMembership->user->name)
        ->toBe($userMembership->user_name);
});

test('user active membership returns current active membership', function () {
    $this->seed(MembershipSeeder::class);

    $silverMembership = Membership::where('code', 'S')->first();
    $goldMembership = Membership::where('code', 'G')->first();
    $user = User::factory()->create(['gender' => 'female']);

    // Get current prices
    $silverPrice = $silverMembership->prices->first();
    $goldPrice = $goldMembership->prices->first();

    // Create expired membership
    UserMembership::create([
        'user_id' => $user->id,
        'membership_id' => $silverMembership->id,
        'membership_number' => 'S001',
        'user_name' => $user->name,
        'user_email' => $user->email,
        'user_gender' => $user->gender,
        'membership_name' => $silverMembership->name,
        'membership_price_at_joining' => $silverPrice->female,
        'start_date' => now()->subYears(2),
        'end_date' => now()->subYear(),
    ]);

    // Create active membership
    $activeMembership = UserMembership::create([
        'user_id' => $user->id,
        'membership_id' => $goldMembership->id,
        'membership_number' => 'G001',
        'user_name' => $user->name,
        'user_email' => $user->email,
        'user_gender' => $user->gender,
        'membership_name' => $goldMembership->name,
        'membership_price_at_joining' => $goldPrice->female,
        'start_date' => now()->subMonth(),
        'end_date' => now()->addMonths(11),
    ]);

    // Create future membership
    UserMembership::create([
        'user_id' => $user->id,
        'membership_id' => $goldMembership->id,
        'membership_number' => 'G002',
        'user_name' => $user->name,
        'user_email' => $user->email,
        'user_gender' => $user->gender,
        'membership_name' => $goldMembership->name,
        'membership_price_at_joining' => $goldPrice->female,
        'start_date' => now()->addYear(),
        'end_date' => now()->addYears(2),
    ]);

    expect($user->activeUserMembership)
        ->toBeInstanceOf(UserMembership::class)
        ->and($user->activeUserMembership->id)->toBe($activeMembership->id)
        ->and($user->userMemberships)->toHaveCount(3);
});

test('user membership can be soft deleted', function () {
    $this->seed(MembershipSeeder::class);
    $user = User::factory()->create(['gender' => 'female']);
    $membership = Membership::where('code', 'S')->first();

    $userMembership = UserMembership::create([
        'user_id' => $user->id,
        'membership_id' => $membership->id,
        'membership_number' => 'S001',
        'user_name' => $user->name,
        'user_email' => $user->email,
        'user_gender' => $user->gender,
        'membership_name' => $membership->name,
        'membership_price_at_joining' => $membership->prices->first()->female,
        'start_date' => now(),
        'end_date' => now()->addYear(),
    ]);

    $userMembership->delete();

    expect($userMembership->deleted_at)->not->toBeNull()
        ->and(UserMembership::withTrashed()->find($userMembership->id)->deleted_at)->not->toBeNull();
});
