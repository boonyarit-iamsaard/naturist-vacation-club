<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserMembership extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'membership_id',
        'membership_number',
        'user_name',
        'user_email',
        'user_gender',
        'membership_name',
        'membership_price_at_joining',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'membership_price_at_joining' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Generate or retrieve a unique membership number.
     *
     * @param  int  $userId  The ID of the user.
     * @param  int  $membershipId  The ID of the membership type.
     * @return string The unique membership number.
     */
    public static function findOrCreateMembershipNumber($userId, $membershipId)
    {
        /**
         * Fetch the membership type code based on membership ID.
         */
        $membership = Membership::find($membershipId);
        $membershipCode = $membership->code;

        /**
         * Fetch any existing membership for this user and membership type
         */
        $existingMembership = self::where('user_id', $userId)
            ->where('membership_id', $membershipId)
            ->orderBy('start_date', 'desc')
            ->first();

        if ($existingMembership) {
            /**
             * Return the existing membership number
             */
            return $existingMembership->membership_number;
        }

        /**
         * Fetch the last number for the membership type.
         */
        $sequence = MembershipSequence::where('membership_code', $membershipCode)
            ->lockForUpdate()
            ->first();

        $newSequence = $sequence ? $sequence->last_assigned_sequence + 1 : 1;

        /**
         * Update the sequence number for the membership type.
         */
        MembershipSequence::updateOrCreate(
            ['membership_code' => $membershipCode],
            ['last_assigned_sequence' => $newSequence]
        );

        /**
         * Pad the sequence number to ensure a minimum of 4 digits.
         */
        $paddedSequence = str_pad((string) $newSequence, 4, '0', STR_PAD_LEFT);

        /**
         * Return the generated membership number.
         */
        return "{$membershipCode}{$paddedSequence}";
    }

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            /**
             * Set the end date to one year after the start date if not provided.
             */
            $model->setEndDate();

            /**
             * Generate a membership number if not provided.
             */
            if ($model->membership_number === null) {
                $model->membership_number = self::findOrCreateMembershipNumber($model->user_id, $model->membership_id);
            }
        });

        static::updating(function ($model) {
            /**
             * Set the end date to one year after the start date if not provided.
             */
            $model->setEndDate();
        });
    }

    /**
     * @return BelongsTo<User, covariant UserMembership>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Membership, covariant UserMembership>
     */
    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class);
    }

    /**
     * Set the end date to one year after the start date if not provided.
     */
    private function setEndDate(): void
    {
        if ($this->end_date === null && $this->start_date !== null) {
            $this->end_date = Carbon::parse($this->start_date)->addYear();
        }
    }
}
