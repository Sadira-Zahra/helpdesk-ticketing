<?php

namespace App\Http\Controllers;

use App\Models\Tiket;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

        // STATISTIK DASAR BERDASARKAN ROLE - SESUAIKAN DENGAN TIKET INDEX
        if ($role === 'administrator') {
            // Administrator lihat semua tiket KECUALI CLOSED di dashboard
            $totalTiket = Tiket::whereNotIn('status', ['closed'])->count();
            $tiketOpen = Tiket::where('status', 'pending')->count(); // PERBAIKI: Tiket Baru = PENDING
            $tiketProgress = Tiket::whereIn('status', ['progress'])->count(); // Hanya progress
            $tiketFinish = Tiket::where('status', 'finish')->count();
            $tiketClosed = Tiket::where('status', 'closed')->count();
            
            $tiketTerbaru = Tiket::with('user', 'departemen', 'teknisi')
                ->whereNotIn('status', ['closed'])
                ->latest()
                ->limit(10)
                ->get();
                
        } elseif ($role === 'admin') {
            // Admin lihat tiket departemennya KECUALI CLOSED
            $totalTiket = Tiket::where('departemen_id', $user->departemen_id)
                ->whereIn('status', ['open', 'pending', 'progress', 'finish'])
                ->count();
            $tiketOpen = Tiket::where('departemen_id', $user->departemen_id)
                ->where('status', 'pending') // PERBAIKI: Tiket Baru = PENDING
                ->count();
            $tiketProgress = Tiket::where('departemen_id', $user->departemen_id)
                ->where('status', 'progress') // Hanya progress
                ->count();
            $tiketFinish = Tiket::where('departemen_id', $user->departemen_id)
                ->where('status', 'finish')
                ->count();
            $tiketClosed = Tiket::where('departemen_id', $user->departemen_id)
                ->where('status', 'closed')
                ->count();
            
            $tiketTerbaru = Tiket::with('user', 'departemen', 'teknisi')
                ->where('departemen_id', $user->departemen_id)
                ->whereIn('status', ['open', 'pending', 'progress', 'finish'])
                ->latest()
                ->limit(10)
                ->get();
                
        } elseif ($role === 'teknisi') {
            // Teknisi hanya lihat tiket yang di-assign ke dia KECUALI CLOSED
            $totalTiket = Tiket::where('teknisi_id', $user->id)
                ->whereIn('status', ['pending', 'progress', 'finish'])
                ->count();
            $tiketOpen = Tiket::where('teknisi_id', $user->id)
                ->where('status', 'pending') // PERBAIKI: Tiket Baru = PENDING
                ->count();
            $tiketProgress = Tiket::where('teknisi_id', $user->id)
                ->where('status', 'progress') // Hanya progress
                ->count();
            $tiketFinish = Tiket::where('teknisi_id', $user->id)
                ->where('status', 'finish')
                ->count();
            $tiketClosed = Tiket::where('teknisi_id', $user->id)
                ->where('status', 'closed')
                ->count();
            
            $tiketTerbaru = Tiket::with('user', 'departemen', 'teknisi')
                ->where('teknisi_id', $user->id)
                ->whereIn('status', ['pending', 'progress', 'finish'])
                ->latest()
                ->limit(10)
                ->get();
                
        } else {
            // User biasa hanya lihat tiket miliknya KECUALI CLOSED
            $totalTiket = Tiket::where('user_id', $user->id)
                ->whereIn('status', ['open', 'pending', 'progress', 'finish'])
                ->count();
            $tiketOpen = Tiket::where('user_id', $user->id)
                ->where('status', 'pending') // PERBAIKI: Tiket Baru = PENDING
                ->count();
            $tiketProgress = Tiket::where('user_id', $user->id)
                ->where('status', 'progress') // Hanya progress
                ->count();
            $tiketFinish = Tiket::where('user_id', $user->id)
                ->where('status', 'finish')
                ->count();
            $tiketClosed = Tiket::where('user_id', $user->id)
                ->where('status', 'closed')
                ->count();
            
            $tiketTerbaru = Tiket::with('user', 'departemen', 'teknisi')
                ->where('user_id', $user->id)
                ->whereIn('status', ['open', 'pending', 'progress', 'finish'])
                ->latest()
                ->limit(10)
                ->get();
        }

        // KHUSUS ADMINISTRATOR - SLA METRICS
        if ($role === 'administrator') {
            $activeTikets = Tiket::whereNotIn('status', ['finish', 'closed'])->get();
            $slaLimit = 24; // jam

            // Priority Distribution
            $priorityHigh = $activeTikets->filter(function($tiket) {
                return $tiket->created_at->diffInHours(now()) > 24;
            })->count();
            
            $priorityMedium = $activeTikets->filter(function($tiket) {
                $hours = $tiket->created_at->diffInHours(now());
                return $hours <= 24 && $hours > 72;
            })->count();
            
            $priorityLow = $activeTikets->filter(function($tiket) {
                return $tiket->created_at->diffInHours(now()) <= 72;
            })->count();

            // SLA Status
            $tiketOnTime = $activeTikets->filter(function($tiket) use ($slaLimit) {
                $hours = $tiket->created_at->diffInHours(now());
                return $hours < ($slaLimit - 4);
            })->count();
            
            $tiketAtRisk = $activeTikets->filter(function($tiket) use ($slaLimit) {
                $hours = $tiket->created_at->diffInHours(now());
                return $hours >= ($slaLimit - 4) && $hours < $slaLimit;
            })->count();
            
            $tiketBreached = $activeTikets->filter(function($tiket) use ($slaLimit) {
                $hours = $tiket->created_at->diffInHours(now());
                return $hours >= $slaLimit;
            })->count();

            // SLA Compliance
            $totalSla = $tiketOnTime + $tiketAtRisk + $tiketBreached;
            $slaCompliance = $totalSla > 0 ? round(($tiketOnTime / $totalSla) * 100, 1) : 100;

            // Tiket At-Risk
            $atRiskTickets = Tiket::with('user', 'departemen')
                ->whereNotIn('status', ['finish', 'closed'])
                ->get()
                ->filter(function($tiket) use ($slaLimit) {
                    $hours = $tiket->created_at->diffInHours(now());
                    return $hours >= ($slaLimit - 4) && $hours < $slaLimit;
                })
                ->sortByDesc('created_at')
                ->take(10);

            // Tiket Breached
            $breachedTickets = Tiket::with('user', 'departemen')
                ->whereNotIn('status', ['finish', 'closed'])
                ->get()
                ->filter(function($tiket) use ($slaLimit) {
                    $hours = $tiket->created_at->diffInHours(now());
                    return $hours >= $slaLimit;
                })
                ->sortByDesc('created_at')
                ->take(10);

            // Data untuk modal detail tiket
            $urgencies = \App\Models\Urgency::orderBy('jam')->get();
            $teknisis = \App\Models\User::where('role', 'teknisi')->get();

            return view('dashboard', compact(
                'totalTiket', 'tiketOpen', 'tiketProgress', 'tiketFinish', 'tiketClosed',
                'priorityHigh', 'priorityMedium', 'priorityLow',
                'slaCompliance', 'tiketOnTime', 'tiketAtRisk', 'tiketBreached',
                'atRiskTickets', 'breachedTickets', 'tiketTerbaru',
                'urgencies', 'teknisis'
            ));
        }

        // NON-ADMINISTRATOR
        // Data untuk modal detail tiket
        $urgencies = \App\Models\Urgency::orderBy('jam')->get();
        $teknisis = \App\Models\User::where('role', 'teknisi')->get();

        return view('dashboard', compact(
            'totalTiket', 'tiketOpen', 'tiketProgress', 'tiketFinish', 'tiketClosed',
            'tiketTerbaru',
            'urgencies', 'teknisis'
        ));
    }
}
