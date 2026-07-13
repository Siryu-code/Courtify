<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venue;
use App\Models\Promotion;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Venue::query();

        // Search
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sort
        $sort = $request->sort ?? 'name_asc';
        match ($sort) {
            'name_asc'    => $query->orderBy('name', 'asc'),
            'name_desc'   => $query->orderBy('name', 'desc'),
            'price_asc'   => $query->orderBy('price_per_hour', 'asc'),
            'price_desc'  => $query->orderBy('price_per_hour', 'desc'),
            default       => $query->orderBy('name', 'asc'),
        };

        $venues = $query->with('images', 'ratings')->get();
        $promotions = Promotion::whereDate('start_date', '<=', now())
                               ->whereDate('end_date', '>=', now())
                               ->get();

        return view('home', compact('venues', 'promotions', 'sort'));
    }
}