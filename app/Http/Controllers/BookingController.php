<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Venue;
use App\Models\Promotion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index($venue_id)
    {
        $venue = Venue::with('images')->findOrFail($venue_id);
        $promotions = Promotion::whereDate('start_date', '<=', now())
                               ->whereDate('end_date', '>=', now())
                               ->get();

        return view('booking.index', compact('venue', 'promotions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'venue_id'      => 'required|exists:venues,id',
            'customer_name' => 'required|string',
            'phone'         => 'required|string',
            'booking_date'  => 'required|date',
            'end_date'      => 'required|date|after_or_equal:booking_date',
            'start_time'    => 'required',
            'end_time'      => 'required',
            'is_multiday'   => 'boolean',
            'customer_note' => 'nullable|string',
            'promotion_id'  => 'nullable|exists:promotions,id',
        ]);

        // Cek bentrok
        $bentrok = Booking::where('venue_id', $data['venue_id'])
            ->where(function ($query) use ($data) {
                $query->where(function ($q) use ($data) {
                    $q->where('booking_date', '<=', $data['end_date'])
                      ->where('end_date', '>=', $data['booking_date']);
                })->where(function ($q) use ($data) {
                    $q->where('start_time', '<', Carbon::parse($data['end_time'])->addHour()->format('H:i:s'))
                      ->where('end_time', '>', $data['start_time']);
                });
            })->exists();

        if ($bentrok) {
            return redirect()->back()->with('error', 'Lapangan sudah dibooking pada waktu tersebut.');
        }

        // Hitung harga
        $venue = Venue::findOrFail($data['venue_id']);
        $durasiJam = Carbon::parse($data['start_time'])->diffInHours(Carbon::parse($data['end_time']));
        
        if ($data['is_multiday'] ?? false) {
            $jumlahHari = Carbon::parse($data['booking_date'])->diffInDays(Carbon::parse($data['end_date'])) + 1;
            $price = $venue->price_per_hour * $durasiJam * $jumlahHari;
        } else {
            $price = $venue->price_per_hour * $durasiJam;
        }

        $adminFee = 5000;
        $totalPrice = $price + $adminFee;

        // Hitung diskon promo
        if ($data['promotion_id']) {
            $promo = Promotion::findOrFail($data['promotion_id']);
            $discount = $totalPrice * ($promo->discount / 100);
            $totalPrice = $totalPrice - $discount;
        }

        // Simpan booking
        Booking::create([
            'booking_code'  => 'CA-' . strtoupper(Str::random(8)),
            'user_id'       => Auth::id(),
            'venue_id'      => $data['venue_id'],
            'promotion_id'  => $data['promotion_id'] ?? null,
            'customer_name' => $data['customer_name'],
            'phone'         => $data['phone'],
            'booking_date'  => $data['booking_date'],
            'end_date'      => $data['end_date'],
            'start_time'    => $data['start_time'],
            'end_time'      => $data['end_time'],
            'is_multiday'   => $data['is_multiday'] ?? false,
            'customer_note' => $data['customer_note'] ?? null,
            'price'         => $price,
            'admin_fee'     => $adminFee,
            'total_price'   => $totalPrice,
        ]);

        $booking = Booking::where('user_id', Auth::id())->latest()->first();

        return redirect()->route('booking.success', $booking->id);
    }

    public function confirm($id)
    {
        $booking = Booking::with('venue')->findOrFail($id);
        return view('booking.confirm', compact('booking'));
    }

    public function success($id)
    {
        $booking = Booking::with('venue')->findOrFail($id);
        return view('booking.success', compact('booking'));
    }

    public function history()
    {
        $bookings = Booking::where('user_id', Auth::id())
                           ->with('venue.images')
                           ->latest()
                           ->get();

        return view('booking.history', compact('bookings'));
    }

    public function detail($id)
    {
        $booking = Booking::where('user_id', Auth::id())
                          ->with('venue')
                          ->findOrFail($id);

        return view('booking.detail', compact('booking'));
    }
}