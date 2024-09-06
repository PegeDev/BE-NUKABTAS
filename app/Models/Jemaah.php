<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        "status_pernikahan",
        'mwcnu_id'
    ];





    public function detail(): HasOne
    {
        return $this->hasOne(DetailJemaah::class);
    }

    public function alamat_jemaah(): HasOne
    {
        return $this->hasOne(AlamatJemaah::class);
    }
}
