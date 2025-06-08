<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Absensi;

class HistoryController
{
    public function riwayat(Request $request)
    {
        return view('history');
    }

    public function riwayatData(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $user = $request->user();
        $isEmployee = $user->role === 2;

        $query = Absensi::select('absensis.*')
            ->join('users', 'absensis.user_id', '=', 'users.id');

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        if ($isEmployee) {
            $query->where('absensis.user_id', $user->id);
        }

        $totalData = $query->count();

        $start = $request->input('start', 0);
        $length = $request->input('length', 10);

        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $columns = ['user.username', 'tanggal', 'waktu_masuk', 'waktu_pulang', 'type', 'notes', 'bukti'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'tanggal';

        // Untuk order by username, kita tetap join users dan urutkan dengan benar
        if ($orderColumn === 'user.username') {
            $query->orderBy('users.username', $orderDirection);
        } else {
            $query->orderBy('absensis.' . $orderColumn, $orderDirection);
        }

        $filteredQuery = $query
            ->skip($start)
            ->take($length)
            ->get();

        $data = $filteredQuery->map(function ($item) {
            return [
                'user_id' => $item->user_id,
                'username' => $item->user->username,
                'tanggal' => $item->tanggal,
                'waktu_masuk' => $item->waktu_masuk ?? '-',
                'waktu_pulang' => $item->waktu_pulang ?? '-',
                'type' => $item->type,
                'notes' => $item->notes ?? '-',
                'bukti' => $item->bukti,
            ];
        });

        $totalFiltered = $totalData;

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $totalData,
            "recordsFiltered" => $totalFiltered,
            "data" => $data,
        ]);
    }

}
