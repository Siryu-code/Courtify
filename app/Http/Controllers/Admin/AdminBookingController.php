<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;

class AdminBookingController extends Controller
{
    public function index()
    {
        $date = request('date') ? Carbon::parse(request('date')) : Carbon::today();
        
        $bookings = Booking::with('user', 'venue')
                           ->whereDate('booking_date', '<=', $date)
                           ->whereDate('end_date', '>=', $date)
                           ->get();

        return view('admin.bookings.index', compact('bookings', 'date'));
    }

    public function show($id)
    {
        $booking = Booking::with('user', 'venue', 'promotion')->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }
}