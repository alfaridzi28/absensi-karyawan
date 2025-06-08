<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Absensi;

class DashboardController
{
    public function index()
    {
        $user = Auth::user(); 

        if ($user->isEmployee()) {
            return view('dashboard', [
                'userHasAbsenMasuk' => $user->hasAbsenMasukToday(),
                'userHasAbsenIzin' => $user->hasAbsenIzinToday(),
                'userHasAbsenPulang' => $user->hasAbsenPulangToday(),
            ]);
        }

        $totalUsers = User::count();

        $today = Carbon::now('Asia/Jakarta')->toDateString();

        $usersAbsenToday = Absensi::whereDate('tanggal', $today)
            ->whereIn('type', ['masuk', 'pulang'])
            ->distinct('user_id')
            ->count('user_id');

        $totalIzinToday = Absensi::whereDate('tanggal', $today)
            ->where('type', 'izin')
            ->count();

        return view('dashboard', compact('totalUsers', 'usersAbsenToday', 'totalIzinToday'));
    }
}
