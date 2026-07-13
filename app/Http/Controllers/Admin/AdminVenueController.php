<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venue;
use App\Models\VenueFacility;

class AdminVenueController extends Controller
{
    public function index()
    {
        $venues = Venue::with('images')->get();
        return view('admin.venues.index', compact('venues'));
    }

    public function create()
    {
        return view('admin.venues.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string',
            'type'          => 'required|in:indoor,outdoor',
            'price_per_hour'=> 'required|numeric',
            'location'      => 'required|string',
            'description'   => 'required|string',
            'status'        => 'required|in:available,in_use,maintenance',
            'images'        => 'required|array',
            'images.*'      => 'image|max:2048',
            'facilities'    => 'nullable|array',
            'facilities.*.name'     => 'required|string',
            'facilities.*.icon_svg' => 'required|string',
        ]);

        $venue = Venue::create([
            'name'           => $data['name'],
            'type'           => $data['type'],
            'price_per_hour' => $data['price_per_hour'],
            'location'       => $data['location'],
            'description'    => $data['description'],
            'status'         => $data['status'],
        ]);

        // Simpan gambar
        foreach ($request->file('images') as $image) {
            $path = $image->store('venue_images', 'public');
            $venue->images()->create(['image_path' => $path]);
        }

        // Simpan fasilitas
        if ($request->facilities) {
            foreach ($request->facilities as $facility) {
                $venue->facilities()->create([
                    'name'     => $facility['name'],
                    'icon_svg' => $facility['icon_svg'],
                ]);
            }
        }

        return redirect()->route('admin.venues.index')->with('success', 'Venue berhasil ditambahkan.');
    }

    public function show($id)
    {
        $venue = Venue::with('images', 'facilities', 'ratings')->findOrFail($id);
        return view('admin.venues.show', compact('venue'));
    }

    public function edit($id)
    {
        $venue = Venue::with('images', 'facilities')->findOrFail($id);
        return view('admin.venues.edit', compact('venue'));
    }

    public function update(Request $request, $id)
    {
        $venue = Venue::findOrFail($id);

        $data = $request->validate([
            'name'           => 'required|string',
            'type'           => 'required|in:indoor,outdoor',
            'price_per_hour' => 'required|numeric',
            'location'       => 'required|string',
            'description'    => 'required|string',
            'status'         => 'required|in:available,in_use,maintenance',
            'images'         => 'nullable|array',
            'images.*'       => 'image|max:2048',
        ]);

        $venue->update([
            'name'           => $data['name'],
            'type'           => $data['type'],
            'price_per_hour' => $data['price_per_hour'],
            'location'       => $data['location'],
            'description'    => $data['description'],
            'status'         => $data['status'],
        ]);

        // Tambah gambar baru kalau ada
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('venue_images', 'public');
                $venue->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('admin.venues.index')->with('success', 'Venue berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $venue = Venue::findOrFail($id);
        $venue->delete();

        return redirect()->route('admin.venues.index')->with('success', 'Venue berhasil dihapus.');
    }
}