<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = ['user_id', 'tanggal', 'type', 'notes', 'bukti', 'waktu_masuk', 'waktu_pulang'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
