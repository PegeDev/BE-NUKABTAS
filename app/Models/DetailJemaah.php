<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailJemaah extends Model
{
    use HasFactory;

    protected $fillable = [
        "penghasilan",
        "pekerjaan",
        "foto_ktp",
        "alamat_detail",
        "pendidikan_terakhir",
        "riwayat_pendidikan",
        "riwayat_pesantren",
        "riwayat_kaderisasi",
        "sertifikat_saada",
        "riwayat_organisasi",
        "riwayat_organisasi_external",
        "status_pernikahan",
        "jemaah_id",
    ];


    protected $casts = [
        "riwayat_pendidikan" => "array",
        "riwayat_pesantren" => "array",
        "riwayat_kaderisasi" => "array",
        "riwayat_organisasi" => "array",
        "riwayat_organisasi_external" => "array",
    ];

    public function jemaah()
    {
        return $this->hasOne(Jemaah::class);
    }
}
