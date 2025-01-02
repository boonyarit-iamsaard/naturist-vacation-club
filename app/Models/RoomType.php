<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'room_price_id',
    ];

    /**
     * @return HasMany<Room, covariant RoomType>
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }
}
