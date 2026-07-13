<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VenueFacility extends Model
{
    protected $fillable = [
        'venue_id',
        'name',
        'icon_svg',
    ];

    // Relasi ke Venue (many-to-one)
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
}
