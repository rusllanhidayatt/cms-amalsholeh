<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Statistic;

class StatisticController extends Controller
{
    // POST /api/v1/statistics
    public function store(Request $request)
    {
        $data = $request->validate([
            'event'         => 'required|string|max:255',
            'utm_source'    => 'nullable|string|max:255',
            'utm_medium'    => 'nullable|string|max:255',
            'utm_campaign'  => 'nullable|string|max:255',
        ]);

        $statistic = Statistic::create([
            'event'         => $data['event'],
            'user_agent'    => $request->userAgent(),
            'ip'            => $request->ip(),
            'utm_source'    => $data['utm_source'] ?? null,
            'utm_medium'    => $data['utm_medium'] ?? null,
            'utm_campaign'  => $data['utm_campaign'] ?? null,
        ]);

        return response()->json([
            'message' => 'Statistik Successfully.',
            'data'    => $statistic
        ], 201);
    }

    // GET /api/v1/statistics
    public function index()
    {
        $statistics = Statistic::latest()->get();

        return response()->json([
            'data' => $statistics
        ]);
    }
}
