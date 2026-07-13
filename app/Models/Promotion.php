<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'title',
        'description',
        'discount',
        'banner_image',
        'is_first_booking',
        'start_date',
        'end_date',
    ];

    // Relasi ke Booking (one-to-many)
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
