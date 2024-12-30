<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Membership extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'membership_price_id',
        'room_discount',
    ];

    protected $casts = [
        'room_discount' => 'integer',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($membership) {
            /**
             * Check if a sequence entry already exists for the membership code.
             */
            if (! MembershipSequence::where('membership_code', $membership->code)->exists()) {
                /**
                 * Create a new sequence entry for the membership code.
                 */
                MembershipSequence::create([
                    'membership_code' => $membership->code,
                    'last_assigned_sequence' => 0,
                ]);
            }
        });
    }

    /**
     * @return BelongsTo<MembershipPrice, covariant Membership>
     */
    public function price(): BelongsTo
    {
        return $this->belongsTo(MembershipPrice::class, 'membership_price_id');
    }

    /**
     * @return HasMany<UserMembership, covariant Membership>
     */
    public function userMemberships(): HasMany
    {
        return $this->hasMany(UserMembership::class);
    }
}
