<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MembershipSequence extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'membership_code',
        'last_assigned_sequence',
    ];

    protected $casts = [
        'last_assigned_sequence' => 'integer',
    ];
}
