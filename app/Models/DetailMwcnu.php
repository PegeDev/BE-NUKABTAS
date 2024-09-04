<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailMwcnu extends Model
{
    use HasFactory;


    protected $fillable = [
        'mwcnu_id',
        'nama_ketua',
        'telp_ketua',
        'email',
        'alamat',
        'google_maps',
        "nama_admin",
        "telp_admin",
        "email_admin",
        "alamat_admin",
        "surat_tugas",
        "status",
    ];

    public function mwcnu()
    {
        return $this->belongsTo(Mwcnu::class);
    }
}
