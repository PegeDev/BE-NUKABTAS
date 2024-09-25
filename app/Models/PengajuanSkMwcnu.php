<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanSkMwcnu extends Model
{
    use HasFactory;


    protected $fillable = [
        "surat_permohonan",
        "ba_konferensi",
        "ba_rapat_formatur",
        "daftar_riwayat_hidup",
        "kta",
        "sertifikat_kaderisasi",
        "kesediaan",
        "ktp",
        "dok_ba_konferensi",
        "dok_ba_rapat_formatur",
    ];

    protected $casts = [
        "dok_ba_konferensi" => "array",
        "dok_ba_rapat_formatur" => "array",
    ];
}
