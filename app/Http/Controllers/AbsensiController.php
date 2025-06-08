<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;
use Carbon\Carbon;

class AbsensiController
{
    public function index()
    {
        $user = Auth::user();
        return view('dashboard', compact('user'));
    }
    
    public function absenPost(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::now('Asia/Jakarta')->toDateString();

        $type = $request->input('type');
        $notes = $request->input('notes'); // untuk izin

        // Cek data absensi hari ini
        $absensi = Absensi::where('user_id', $user->id)
                        ->where('tanggal', $today)
                        ->first();

        if ($type === 'masuk') {
            if ($absensi) {
                return back()->with('error', 'Anda sudah absen masuk hari ini.');
            }

            Absensi::create([
                'user_id' => $user->id,
                'tanggal' => $today,
                'type' => 'masuk',
                'waktu_masuk' => Carbon::now('Asia/Jakarta')->toTimeString(),
                'notes' => null,
            ]);

            return back()->with('success', 'Absen masuk berhasil.');
        }

        if ($type === 'pulang') {
            if (!$absensi) {
                return back()->with('error', 'Anda belum absen masuk hari ini.');
            }

            if ($absensi->waktu_pulang) {
                return back()->with('error', 'Anda sudah absen pulang hari ini.');
            }

            $absensi->update([
                'type' => 'pulang',
                'waktu_pulang' => Carbon::now('Asia/Jakarta')->toTimeString(),
            ]);

            return back()->with('success', 'Absen pulang berhasil.');
        }

        if ($type === 'izin') {

            // Validasi dulu
            $request->validate([
                'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'notes' => 'required|string',
            ], [
                'notes.required' => 'Catatan izin wajib diisi.',
            ]);

            if ($absensi) {
                return back()->with('error', 'Anda sudah melakukan absen hari ini, tidak bisa izin.');
            }

            $filename = null;
            if ($request->hasFile('bukti')) {
                $file = $request->file('bukti');
                $filename = time() . '_' . $file->getClientOriginalName();

                $userFolder = 'izin/' . auth()->id(); 
                $path = $file->storeAs($userFolder, $filename, 'public'); // Simpan di storage/app/public/izin/{user_id}
            }

            Absensi::create([
                'user_id' => $user->id,
                'tanggal' => $today,
                'type' => 'izin',
                'waktu_masuk' => null,
                'waktu_pulang' => null,
                'notes' => $notes,
                'bukti' => $filename,
            ]);

            return back()->with('success', 'Izin berhasil diajukan.');
        }

        return back()->with('error', 'Tipe absen tidak valid.');
    }
}
