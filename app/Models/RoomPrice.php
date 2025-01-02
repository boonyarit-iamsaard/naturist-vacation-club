<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomPrice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'weekday',
        'weekend',
    ];

    protected $casts = [
        'weekday' => 'integer',
        'weekend' => 'integer',
    ];

    /**
     * @return HasMany<RoomType, covariant RoomPrice>
     */
    public function roomTypes(): HasMany
    {
        return $this->hasMany(RoomType::class);
    }
}
