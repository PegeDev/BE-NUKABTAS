<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Jemaah extends Model
{
    use HasFactory;
    use HasRoles;


    protected $fillable = [
        "nama_lengkap",
        "nama_panggilan",
        "nik",
        "telp",
        "email",
        "tempat_lahir",
        "tanggal_lahir",
        "jenis_kelamin",
        "profile_picture",
        "alamat_lengkap",
        "kepengurusan",
        "jabatan_kepengurusan",
        "alamat_lengkap",
        'mwcnu_id'
    ];

    protected $casts = [
        'alamat_lengkap' => 'json',
    ];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function detail()
    {
        return $this->hasOne(DetailJemaah::class);
    }
}
