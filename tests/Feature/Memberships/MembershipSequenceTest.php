<?php

use App\Models\Membership;
use App\Models\MembershipSequence;
use Database\Seeders\MembershipSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('membership sequence is created automatically', function () {
    $this->seed(MembershipSeeder::class);

    $sequence = MembershipSequence::where('membership_code', 'S')->first();

    expect($sequence)
        ->not->toBeNull()
        ->membership_code->toBe('S')
        ->last_assigned_sequence->toBe(0);
});

test('all memberships have sequences', function () {
    $this->seed(MembershipSeeder::class);

    $memberships = Membership::all();

    $memberships->each(function ($membership) {
        expect(MembershipSequence::where('membership_code', $membership->code)->exists())
            ->toBeTrue("Sequence not found for membership code: {$membership->code}");
    });
});

test('sequence starts at zero', function () {
    $this->seed(MembershipSeeder::class);

    $sequences = MembershipSequence::all();

    $sequences->each(function ($sequence) {
        expect($sequence->last_assigned_sequence)
            ->toBe(0, "Sequence for {$sequence->membership_code} should start at 0");
    });
});

test('sequence can be soft deleted', function () {
    $this->seed(MembershipSeeder::class);
    $sequence = MembershipSequence::first();

    $sequence->delete();

    expect($sequence->deleted_at)->not->toBeNull()
        ->and(MembershipSequence::withTrashed()->find($sequence->id)->deleted_at)->not->toBeNull();
});
