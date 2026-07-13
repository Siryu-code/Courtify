<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $totalBooking = Booking::where('user_id', $user->id)->count();

        return view('profile.index', compact('user', 'totalBooking'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'phone'         => 'required|string|max:15',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $data['profile_photo'] = $path;
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}