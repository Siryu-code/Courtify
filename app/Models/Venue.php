<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $fillable = [
        'name',
        'type',
        'price_per_hour',
        'location',
        'description',
        'status',
    ];

    // Relasi ke VenueImage (one-to-many)
    public function images()
    {
        return $this->hasMany(VenueImage::class);
    }

    // Relasi ke VenueFacility (one-to-many)
    public function facilities()
    {
        return $this->hasMany(VenueFacility::class);
    }

    // Relasi ke VenueRating (one-to-many)
    public function ratings()
    {
        return $this->hasMany(VenueRating::class);
    }

    // Relasi ke Booking (one-to-many)
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
