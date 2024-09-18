<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RantingNuMwcnu extends Model
{
    use HasFactory;

    protected $fillable = [
        "posisi",
        "jabatan",
        "jemaah_id",
        "kepengurusan_id",
        "village_id",
        "masa_khidmat",
    ];

    protected $casts = [
        "masa_khidmat" => "array"
    ];

    public function jemaah(): BelongsTo
    {
        return $this->belongsTo(Jemaah::class);
    }

    public function desa()
    {
        return $this->belongsTo(Village::class);
    }


    public function getNamaLengkapAttribute()
    {
        return $this->jemaah->nama_lengkap;
    }

    public function getJenisKelaminAttribute()
    {
        return $this->jemaah->jenis_kelamin;
    }

    public function getProfilePictureAttribute()
    {
        return $this->jemaah->profile_picture;
    }
    public function getAlamatJemaahAttribute()
    {
        return $this->jemaah->alamat_jemaah;
    }
    public function getEmailAttribute()
    {
        return $this->jemaah->email;
    }

    public function getNikAttribute()
    {
        return $this->jemaah->nik;
    }
    public function getTelpAttribute()
    {
        return $this->jemaah->telp;
    }
    public function getTanggalLahirAttribute()
    {
        return $this->jemaah->tanggal_lahir;
    }
}
