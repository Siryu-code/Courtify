<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VenueImage extends Model
{
    protected $fillable = [
        'venue_id',
        'image_path',
    ];

    // Relasi ke Venue (many-to-one)
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
}
