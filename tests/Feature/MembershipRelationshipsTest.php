<?php

use App\Models\Membership;
use App\Models\MembershipPrice;
use App\Models\MembershipSequence;
use App\Models\User;
use App\Models\UserMembership;
use Database\Seeders\MembershipSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('membership belongs to membership price', function () {
    $this->seed();
    $membership = Membership::where('code', 'S')->first();

    expect($membership->price)
        ->toBeInstanceOf(MembershipPrice::class)
        ->and($membership->price->female)->toBe(500000)
        ->and($membership->price->male)->toBe(0);
});

test('membership price has many memberships', function () {
    $this->seed();
    $silverMembership = Membership::where('code', 'S')->first();
    $price = $silverMembership->price;

    expect($price->memberships)
        ->toBeCollection()
        ->toHaveCount(1)
        ->and($price->memberships->first()->id)->toBe($silverMembership->id);
});

test('membership has many user memberships', function () {
    $this->seed();
    $membership = Membership::where('code', 'G')->first();

    expect($membership->userMemberships)
        ->toBeCollection()
        ->not->toBeEmpty()
        ->and($membership->userMemberships->first())
        ->toBeInstanceOf(UserMembership::class);
});

test('user membership belongs to membership', function () {
    $this->seed();
    $userMembership = UserMembership::first();

    expect($userMembership->membership)
        ->toBeInstanceOf(Membership::class)
        ->and($userMembership->membership->name)->not->toBeEmpty();
});

test('user has many user memberships', function () {
    $this->seed();
    $user = User::whereHas('userMemberships')->first();

    expect($user->userMemberships)
        ->toBeCollection()
        ->not->toBeEmpty()
        ->and($user->userMemberships->first())
        ->toBeInstanceOf(UserMembership::class);
});

test('user membership belongs to user', function () {
    $this->seed();
    $userMembership = UserMembership::first();

    expect($userMembership->user)
        ->toBeInstanceOf(User::class)
        ->and($userMembership->user->name)->toBe($userMembership->user_name);
});

test('user active membership returns current active membership', function () {
    $this->seed();

    $silverMembership = Membership::where('code', 'S')->first();
    $goldMembership = Membership::where('code', 'G')->first();

    $user = User::factory()->create(['gender' => 'female']);

    // Create expired membership
    $expiredMembership = new UserMembership;
    $expiredMembership->user_id = $user->id;
    $expiredMembership->membership_id = $silverMembership->id;
    $expiredMembership->user_name = $user->name;
    $expiredMembership->user_email = $user->email;
    $expiredMembership->user_gender = $user->gender;
    $expiredMembership->membership_name = $silverMembership->name;
    $expiredMembership->membership_price_at_joining = $silverMembership->price->female;
    $expiredMembership->start_date = now()->subYears(2);
    $expiredMembership->end_date = now()->subYear();
    $expiredMembership->save();

    // Create active membership
    $activeMembership = new UserMembership;
    $activeMembership->user_id = $user->id;
    $activeMembership->membership_id = $goldMembership->id;
    $activeMembership->user_name = $user->name;
    $activeMembership->user_email = $user->email;
    $activeMembership->user_gender = $user->gender;
    $activeMembership->membership_name = $goldMembership->name;
    $activeMembership->membership_price_at_joining = $goldMembership->price->female;
    $activeMembership->start_date = now()->subMonth();
    $activeMembership->end_date = now()->addMonths(11);
    $activeMembership->save();

    // Create future membership
    $futureMembership = new UserMembership;
    $futureMembership->user_id = $user->id;
    $futureMembership->membership_id = $goldMembership->id;
    $futureMembership->user_name = $user->name;
    $futureMembership->user_email = $user->email;
    $futureMembership->user_gender = $user->gender;
    $futureMembership->membership_name = $goldMembership->name;
    $futureMembership->membership_price_at_joining = $goldMembership->price->female;
    $futureMembership->start_date = now()->addYear();
    $futureMembership->end_date = now()->addYears(2);
    $futureMembership->save();

    expect($user->activeUserMembership)
        ->toBeInstanceOf(UserMembership::class)
        ->and($user->activeUserMembership->id)->toBe($activeMembership->id)
        ->and($user->userMemberships)->toHaveCount(3);
});

test('membership sequence is created automatically on membership creation', function () {
    // Only seed memberships to ensure sequence starts at 0
    $this->seed(MembershipSeeder::class);

    // Verify sequence is created for silver membership
    $sequence = MembershipSequence::where('membership_code', 'S')->first();
    expect($sequence)
        ->not->toBeNull()
        ->and($sequence->membership_code)->toBe('S')
        ->and($sequence->last_assigned_sequence)->toBe(0);

    // Verify sequence is created for gold membership
    expect(MembershipSequence::where('membership_code', 'G')->count())->toBe(1)
        ->and(MembershipSequence::where('membership_code', 'G')->first()->last_assigned_sequence)->toBe(0);
});

test('membership creates sequence on creation', function () {
    // Only seed memberships to ensure sequence starts at 0
    $this->seed(MembershipSeeder::class);

    // Verify sequence is created for silver membership
    expect(MembershipSequence::where('membership_code', 'S')->exists())->toBeTrue()
        ->and(MembershipSequence::where('membership_code', 'S')->first())
        ->last_assigned_sequence->toBe(0);
});
