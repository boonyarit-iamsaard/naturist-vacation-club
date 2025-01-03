<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MembershipPrice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'membership_id',
        'type',
        'promotion_name',
        'effective_from',
        'effective_to',
        'female',
        'male',
    ];

    protected $casts = [
        'effective_from' => 'datetime',
        'effective_to' => 'datetime',
        'female' => 'integer',
        'male' => 'integer',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function ($membershipPrice) {
            /**
             * Prevent deletion of standard price if it causes the membership to have no standard prices.
             */
            $remainingStandardPrices = $membershipPrice->membership->prices()
                ->standard()
                ->whereNot('id', $membershipPrice->id)
                ->exists();

            if (! $remainingStandardPrices) {
                throw new Exception('Cannot delete the last standard price for this membership');
            }
        });
    }

    /**
     * @param  Builder<MembershipPrice>  $query
     * @return Builder<MembershipPrice>
     */
    public function scopeStandard(Builder $query): Builder
    {
        return $query->where('type', 'standard');
    }

    /**
     * @param  Builder<MembershipPrice>  $query
     * @return Builder<MembershipPrice>
     */
    public function scopePromotional(Builder $query): Builder
    {
        return $query->where('type', 'promotion');
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
}
