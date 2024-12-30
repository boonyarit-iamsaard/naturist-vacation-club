<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MembershipPrice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'female',
        'male',
    ];

    protected $casts = [
        'female' => 'integer',
        'male' => 'integer',
    ];

    /**
     * @return HasMany<Membership, covariant MembershipPrice>
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }
}
