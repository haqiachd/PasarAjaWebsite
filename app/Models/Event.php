<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'event'; // Nama tabel sesuai dengan struktur
    protected $primaryKey = 'id_event'; // Nama kolom primary key
    public $timestamps = true; // Tidak ada kolom created_at dan updated_at

    // Mass assignable properties
    protected $fillable = [
        'foto', 'judul', 'deskripsi', 'created_at'
    ];
}
