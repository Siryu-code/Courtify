<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promotion;

class PromoController extends Controller
{
    public function index()
    {
        $promotions = Promotion::whereDate('start_date', '<=', now())
                               ->whereDate('end_date', '>=', now())
                               ->get();

        $allPromotions = Promotion::orderBy('start_date', 'desc')->get();

        return view('promos/index', compact('promotions', 'allPromotions'));
    }
}