<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Membership extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
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
     * @return HasMany<MembershipPrice, covariant Membership>
     */
    public function prices(): HasMany
    {
        return $this->hasMany(MembershipPrice::class);
    }

    /**
     * @return HasMany<UserMembership, covariant Membership>
     */
    public function userMemberships(): HasMany
    {
        return $this->hasMany(UserMembership::class);
    }

    /**
     * @return HasOne<MembershipSequence, covariant Membership>
     */
    public function sequence(): HasOne
    {
        return $this->hasOne(MembershipSequence::class, 'membership_code', 'code');
    }
}
