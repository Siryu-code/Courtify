<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'booking_code',
        'user_id',
        'venue_id',
        'promotion_id',
        'customer_name',
        'phone',
        'booking_date',
        'end_date',
        'start_time',
        'end_time',
        'is_multiday',
        'customer_note',
        'price',
        'admin_fee',
        'total_price',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Venue
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    // Relasi ke Promotion
    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }
}
