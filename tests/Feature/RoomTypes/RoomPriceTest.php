<?php

use App\Enums\PriceType;
use App\Models\RoomPrice;
use App\Models\RoomType;
use Database\Seeders\RoomTypeSeeder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('room type has many room prices', function () {
    $this->seed(RoomTypeSeeder::class);

    $roomType = RoomType::first();
    $prices = $roomType->prices();
    $standardPrice = $prices->standard()->active()->first();

    expect($prices)
        ->toBeInstanceOf(HasMany::class)
        ->and($prices->count())->toBeGreaterThan(0)
        ->and($standardPrice->weekday)->toBeGreaterThan(0)
        ->and($standardPrice->weekend)->toBeGreaterThan(0)
        ->and($standardPrice->type)->toBe(PriceType::Standard)
        ->and($standardPrice->effective_from)->not->toBeNull()
        ->and($standardPrice->effective_to)->toBeNull();
});

test('room price belongs to room type', function () {
    $this->seed(RoomTypeSeeder::class);

    $roomType = RoomType::first();
    $price = $roomType->prices->first();

    expect($price->roomType)
        ->toBeInstanceOf(RoomType::class)
        ->and($price->roomType->id)->toBe($roomType->id);
});

test('cannot delete last standard price of room type', function () {
    $this->seed(RoomTypeSeeder::class);

    $roomType = RoomType::first();
    $standardPrice = $roomType->prices()->standard()->active()->first();

    expect(fn () => $standardPrice->delete())
        ->toThrow(Exception::class, 'Cannot delete the last active standard price for this room type');

    expect(fn () => $standardPrice->forceDelete())
        ->toThrow(Exception::class, 'Cannot delete the last active standard price for this room type');
});

test('pool villa room type has correct prices', function () {
    $this->seed(RoomTypeSeeder::class);

    $roomType = RoomType::where('code', 'S')->first();
    $standardPrice = $roomType->prices()->standard()->active()->first();

    expect($standardPrice)
        ->toBeInstanceOf(RoomPrice::class)
        ->and($standardPrice->weekday)->toBe(800000)
        ->and($standardPrice->weekend)->toBe(950000)
        ->and($standardPrice->type)->toBe(PriceType::Standard)
        ->and($standardPrice->effective_from)->not->toBeNull()
        ->and($standardPrice->effective_to)->toBeNull();
});

test('jacuzzi room type has correct prices', function () {
    $this->seed(RoomTypeSeeder::class);

    $roomType = RoomType::where('code', 'A')->first();
    $standardPrice = $roomType->prices()->standard()->active()->first();

    expect($standardPrice)
        ->toBeInstanceOf(RoomPrice::class)
        ->and($standardPrice->weekday)->toBe(340000)
        ->and($standardPrice->weekend)->toBe(390000)
        ->and($standardPrice->type)->toBe(PriceType::Standard)
        ->and($standardPrice->effective_from)->not->toBeNull()
        ->and($standardPrice->effective_to)->toBeNull();
});

test('2nd floor garden view room type has correct prices', function () {
    $this->seed(RoomTypeSeeder::class);

    $roomType = RoomType::where('code', 'E')->first();
    $standardPrice = $roomType->prices()->standard()->active()->first();

    expect($standardPrice)
        ->toBeInstanceOf(RoomPrice::class)
        ->and($standardPrice->weekday)->toBe(190000)
        ->and($standardPrice->weekend)->toBe(240000)
        ->and($standardPrice->type)->toBe(PriceType::Standard)
        ->and($standardPrice->effective_from)->not->toBeNull()
        ->and($standardPrice->effective_to)->toBeNull();
});
