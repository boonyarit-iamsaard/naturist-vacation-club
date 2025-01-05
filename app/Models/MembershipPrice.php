<?php

namespace App\Models;

use App\Enums\PriceType;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use InvalidArgumentException;

class MembershipPrice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'membership_id',
        'membership_name',
        'membership_code',
        'type',
        'promotion_name',
        'effective_from',
        'effective_to',
        'female',
        'male',
    ];

    protected $casts = [
        'type' => PriceType::class,
        'effective_from' => 'datetime',
        'effective_to' => 'datetime',
        'female' => 'integer',
        'male' => 'integer',
    ];

    protected static function boot(): void
    {
        parent::boot();

        // TODO: implement full validation
        // TODO: take effective time into account
        // TODO: set app level configuration for the date format
        // TODO: consider move business logic to a service layer

        static::creating(function ($membershipPrice) {
            if ($membershipPrice->type === PriceType::Standard) {
                $membershipPrice->handleStandardPriceCreation();
            }

            if ($membershipPrice->type === PriceType::Promotion) {
                $membershipPrice->validatePromotionalPriceCreation();
            }
        });

        static::deleting(function ($membershipPrice) {
            $membershipPrice->validateDeletion();
        });
    }

    /**
     * @param  Builder<MembershipPrice>  $query
     * @return Builder<MembershipPrice>
     */
    public function scopeStandard(Builder $query): Builder
    {
        return $query->where('type', PriceType::Standard);
    }

    /**
     * @param  Builder<MembershipPrice>  $query
     * @return Builder<MembershipPrice>
     */
    public function scopePromotional(Builder $query): Builder
    {
        return $query->where('type', PriceType::Promotion);
    }

    /**
     * @param  Builder<MembershipPrice>  $query
     * @return Builder<MembershipPrice>
     */
    public function scopeActive(Builder $query, ?Carbon $date = null): Builder
    {
        $date ??= now();

        return $query
            ->where('effective_from', '<=', $date)
            ->where(function (Builder $query) use ($date) {
                $query
                    ->whereNull('effective_to')
                    ->orWhere('effective_to', '>', $date);
            });
    }

    /**
     * @param  Builder<MembershipPrice>  $query
     * @return Builder<MembershipPrice>
     */
    public function scopeFuture(Builder $query): Builder
    {
        return $query->where('effective_from', '>', now());
    }

    /**
     * @return BelongsTo<Membership, covariant MembershipPrice>
     */
    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class);
    }

    protected function handleStandardPriceCreation(): void
    {
        /** @var MembershipPrice|null $previousStandardPrice */
        $previousStandardPrice = $this->membership->prices()->standard()->latest()->first();

        if ($previousStandardPrice) {
            $previousStandardPrice->effective_to = $this->effective_from->subDay();
            $previousStandardPrice->save();
        }
    }

    protected function validatePromotionalPriceCreation(): void
    {
        $previousPromotionalPrice = $this->membership->prices()->promotional()->latest()->first();

        if ($previousPromotionalPrice && ! $this->effective_from->isAfter($previousPromotionalPrice->effective_from)) {
            throw new InvalidArgumentException('Promotional price must be effective after the previous promotional price');
        }
    }

    protected function validateDeletion(): void
    {
        $remainingStandardPrices = $this->membership->prices()
            ->standard()
            ->active()
            ->whereNot('id', $this->id)
            ->exists();

        if (! $remainingStandardPrices) {
            throw new Exception('Cannot delete the last active standard price for this membership');
        }
    }
}
