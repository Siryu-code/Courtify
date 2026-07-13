<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venue;
use App\Models\VenueRating;
use Illuminate\Support\Facades\Auth;

class VenueController extends Controller
{
    public function show($id)
    {
        $venue = Venue::with('images', 'facilities', 'ratings.user')->findOrFail($id);
        
        $avgRating = $venue->ratings->avg('rating');

        return view('venue.show', compact('venue', 'avgRating'));
    }

    public function storeRating(Request $request)
    {
        $data = $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'rating' => 'required|numeric|min:1|max:5',
        ]);

        VenueRating::updateOrCreate(
            [
                'venue_id' => $data['venue_id'],
                'user_id' => Auth::id(),
            ],
            [
                'rating' => $data['rating'],
            ]
        );

        return redirect()->back()->with('success', 'Rating berhasil disimpan.');
    }
}