<?php

namespace App\Services\Membership;

use App\Enums\MembershipPriceStatus;
use App\Models\Membership;
use App\Models\MembershipPrice;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use InvalidArgumentException;

// TODO: consider implementing price validation
class MembershipPriceService
{
    public function createStandardPrice(
        Membership $membership,
        int $femalePrice,
        int $malePrice,
        Carbon $effectiveFrom
    ): MembershipPrice {
        if ($effectiveFrom->isPast()) {
            throw new InvalidArgumentException('Effective date must be in the future');
        }

        $this->handleOverlappingStandardPrices($membership, $effectiveFrom);

        return MembershipPrice::create([
            'membership_id' => $membership->id,
            'type' => 'standard',
            'female' => $femalePrice,
            'male' => $malePrice,
            'effective_from' => $effectiveFrom,
            'membership_name' => $membership->name,
            'membership_code' => $membership->code,
        ]);
    }

    public function createPromotionalPrice(
        Membership $membership,
        string $promotionName,
        int $femalePrice,
        int $malePrice,
        Carbon $effectiveFrom,
        Carbon $effectiveUntil
    ): MembershipPrice {
        if (empty($promotionName)) {
            throw new InvalidArgumentException('Promotion name is required');
        }

        if ($effectiveFrom->isPast()) {
            throw new InvalidArgumentException('Effective date must be in the future');
        }

        if ($effectiveUntil->lte($effectiveFrom)) {
            throw new InvalidArgumentException('Effective until must be after effective from');
        }

        $this->validateNoOverlappingPromotions($membership, $effectiveFrom, $effectiveUntil);

        return MembershipPrice::create([
            'membership_id' => $membership->id,
            'type' => 'promotion',
            'promotion_name' => $promotionName,
            'female' => $femalePrice,
            'male' => $malePrice,
            'effective_from' => $effectiveFrom,
            'effective_to' => $effectiveUntil,
            'membership_name' => $membership->name,
            'membership_code' => $membership->code,
        ]);
    }

    public function getCurrentPrice(Membership $membership): MembershipPrice
    {
        // First check for active promotions
        $promotionalPrice = $membership->prices()
            ->promotional()
            ->active()
            ->first();

        if ($promotionalPrice) {
            return $promotionalPrice;
        }

        // Fall back to standard price
        return $membership->prices()
            ->standard()
            ->active()
            ->firstOrFail();
    }

    /**
     * @param  Membership  $membership  The membership to get price history for
     * @return Collection<int, covariant array{
     *     id: int,
     *     type: string,
     *     promotion_name: string|null,
     *     female_price: int,
     *     male_price: int,
     *     effective_from: string,
     *     effective_until: string|null,
     *     status: MembershipPriceStatus
     * }>
     */
    public function getPriceHistory(Membership $membership): Collection
    {
        return $membership->prices()
            ->orderBy('effective_from', 'desc')
            ->get()
            ->map(function ($price) {
                return [
                    'id' => $price->id,
                    'type' => $price->type,
                    'promotion_name' => $price->promotion_name,
                    'female_price' => $price->female,
                    'male_price' => $price->male,
                    'effective_from' => $price->effective_from->format('Y-m-d H:i:s'),
                    'effective_until' => $price->effective_to?->format('Y-m-d H:i:s'),
                    'status' => $this->getPriceStatus($price),
                ];
            });
    }

    private function validateNoOverlappingPromotions(
        Membership $membership,
        Carbon $effectiveFrom,
        Carbon $effectiveUntil
    ): void {
        $overlapping = $membership->prices()
            ->promotional()
            ->where(function ($query) use ($effectiveFrom, $effectiveUntil) {
                $query->whereBetween('effective_from', [$effectiveFrom, $effectiveUntil])
                    ->orWhereBetween('effective_until', [$effectiveFrom, $effectiveUntil]);
            })
            ->exists();

        if ($overlapping) {
            throw new InvalidArgumentException('Another promotion already exists during this period');
        }
    }

    private function getPriceStatus(MembershipPrice $price): MembershipPriceStatus
    {
        $now = now();

        if ($price->effective_to && $price->effective_to->lte($now)) {
            return MembershipPriceStatus::Expired;
        }

        if ($price->effective_from->gt($now)) {
            return MembershipPriceStatus::Future;
        }

        return MembershipPriceStatus::Active;
    }

    private function handleOverlappingStandardPrices(
        Membership $membership,
        Carbon $effectiveFrom
    ): void {
        $currentStandardPrice = $membership->prices()
            ->standard()
            ->active()
            ->first();

        if ($currentStandardPrice) {
            $currentStandardPrice->update([
                'effective_to' => $effectiveFrom,
            ]);
        }
    }
}
