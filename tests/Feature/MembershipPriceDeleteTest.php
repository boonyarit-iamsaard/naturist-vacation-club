<?php

use App\Models\Membership;
use App\Models\MembershipPrice;
use Illuminate\Database\QueryException;

test('cannot delete membership price when associated with membership', function () {
    $membershipPrice = MembershipPrice::create([
        'female' => 100,
        'male' => 150,
    ]);

    Membership::create([
        'name' => 'Test Membership',
        'code' => 'TEST',
        'membership_price_id' => $membershipPrice->id,
        'room_discount' => 10,
    ]);

    expect(fn () => $membershipPrice->forceDelete())
        ->toThrow(QueryException::class);
});

test('can delete membership price when not associated', function () {
    $membershipPrice = MembershipPrice::create([
        'female' => 100,
        'male' => 150,
    ]);

    $membershipPrice->forceDelete();

    expect($membershipPrice->exists)->toBeFalse()
        ->and(MembershipPrice::find($membershipPrice->id))->toBeNull();
});
