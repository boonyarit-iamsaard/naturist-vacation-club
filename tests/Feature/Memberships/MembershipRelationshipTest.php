<?php

use App\Models\Membership;
use App\Models\MembershipPrice;
use App\Models\User;
use App\Models\UserMembership;
use Database\Seeders\MembershipSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('membership has many membership prices', function () {
    $this->seed(MembershipSeeder::class);
    $membership = Membership::where('code', 'S')->first();

    expect($membership->prices)
        ->toBeCollection()
        ->not->toBeEmpty()
        ->and($membership->prices->first())
        ->toBeInstanceOf(MembershipPrice::class)
        ->and($membership->prices->first()->female)->toBe(500000)
        ->and($membership->prices->first()->male)->toBe(0)
        ->and($membership->prices->first()->type)->toBe('standard')
        ->and($membership->prices->first()->effective_from)->not->toBeNull()
        ->and($membership->prices->first()->effective_to)->toBeNull()
        ->and($membership->room_discount)->toBe(15);
});

test('gold membership has correct prices', function () {
    $this->seed(MembershipSeeder::class);
    $membership = Membership::where('code', 'G')->first();

    expect($membership->prices)
        ->toBeCollection()
        ->not->toBeEmpty()
        ->and($membership->prices->first())
        ->toBeInstanceOf(MembershipPrice::class)
        ->and($membership->prices->first()->female)->toBe(1000000)
        ->and($membership->prices->first()->male)->toBe(3000000)
        ->and($membership->prices->first()->type)->toBe('standard')
        ->and($membership->prices->first()->effective_from)->not->toBeNull()
        ->and($membership->prices->first()->effective_to)->toBeNull()
        ->and($membership->room_discount)->toBe(30);
});

test('membership price belongs to membership', function () {
    $this->seed(MembershipSeeder::class);

    $silverMembership = Membership::where('code', 'S')->first();
    $price = $silverMembership->prices->first();

    expect($price->membership)
        ->toBeInstanceOf(Membership::class)
        ->and($price->membership->id)->toBe($silverMembership->id);
});

test('membership soft delete sets null on user memberships', function () {
    $this->seed(MembershipSeeder::class);

    $membership = Membership::first();
    $price = $membership->prices->first();

    $user = User::factory()->create(['gender' => 'female']);
    $userMembership = UserMembership::create([
        'user_id' => $user->id,
        'membership_id' => $membership->id,
        'membership_number' => 'S001',
        'user_name' => $user->name,
        'user_email' => $user->email,
        'user_gender' => $user->gender,
        'membership_name' => $membership->name,
        'membership_price_at_joining' => $price->female,
        'start_date' => now(),
        'end_date' => now()->addYear(),
    ]);

    $membership->forceDelete();

    $userMembership->refresh();

    expect($userMembership->membership_id)->toBeNull();
});

test('cannot delete last standard price of membership', function () {
    $this->seed(MembershipSeeder::class);

    $membership = Membership::where('code', 'S')->first();
    $standardPrice = $membership->prices()->standard()->first();

    expect(fn () => $standardPrice->delete())
        ->toThrow(Exception::class, 'Cannot delete the last standard price for this membership');

    expect(fn () => $standardPrice->forceDelete())
        ->toThrow(Exception::class, 'Cannot delete the last standard price for this membership');
});
