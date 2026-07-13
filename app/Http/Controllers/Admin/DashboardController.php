<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Venue;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $totalBookingHariIni = Booking::whereDate('booking_date', $today)->count();
        $totalBookingMingguIni = Booking::whereBetween('booking_date', [$startOfWeek, $endOfWeek])->count();
        
        $pendapatanHariIni = Booking::whereDate('booking_date', $today)->sum('total_price');
        $pendapatanMingguIni = Booking::whereBetween('booking_date', [$startOfWeek, $endOfWeek])->sum('total_price');
        
        $totalVenue = Venue::count();
        $venueAktif = Venue::where('status', 'available')->count();

        // Data chart mingguan
        $weeklyBookings = [];
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->startOfWeek()->addDays($i);
            $weeklyBookings[] = [
                'day'   => $date->translatedFormat('l'),
                'total' => Booking::whereDate('booking_date', $date)->count(),
            ];
        }

        // Recent bookings
        $recentBookings = Booking::with('user', 'venue')
                                 ->latest()
                                 ->take(10)
                                 ->get();

        return view('admin.dashboard', compact(
            'totalBookingHariIni',
            'totalBookingMingguIni',
            'pendapatanHariIni',
            'pendapatanMingguIni',
            'totalVenue',
            'venueAktif',
            'weeklyBookings',
            'recentBookings'
        ));
    }
}