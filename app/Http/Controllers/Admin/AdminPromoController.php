<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promotion;

class AdminPromoController extends Controller
{
    public function index()
    {
        $promotions = Promotion::orderBy('start_date', 'desc')->get();
        return view('admin.promos.index', compact('promotions'));
    }

    public function create()
    {
        return view('admin.promos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'           => 'required|string',
            'description'     => 'required|string',
            'discount'        => 'required|numeric|min:0|max:100',
            'banner_image'    => 'nullable|image|max:2048',
            'is_first_booking'=> 'boolean',
            'start_date'      => 'required|date',
            'end_date'        => 'required|date|after_or_equal:start_date',
        ]);

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')->store('promo_banners', 'public');
        }

        Promotion::create($data);

        return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $promotion = Promotion::findOrFail($id);
        return view('admin.promos.edit', compact('promotion'));
    }

    public function update(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id);

        $data = $request->validate([
            'title'           => 'required|string',
            'description'     => 'required|string',
            'discount'        => 'required|numeric|min:0|max:100',
            'banner_image'    => 'nullable|image|max:2048',
            'is_first_booking'=> 'boolean',
            'start_date'      => 'required|date',
            'end_date'        => 'required|date|after_or_equal:start_date',
        ]);

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')->store('promo_banners', 'public');
        }

        $promotion->update($data);

        return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->delete();

        return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil dihapus.');
    }
}