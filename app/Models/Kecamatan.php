<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Kecamatan extends Model
{
    use HasFactory;
    use HasRoles;



    protected $fillable = [
        'nama',
        "kode"
    ];


    public function desa()
    {
        return $this->hasMany(Desa::class);
    }
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function mwcnu()
    {
        return $this->belongsTo(Mwcnu::class);
    }

    public function pengurus_mwcnu()
    {
        return $this->hasMany(PengurusMwcnu::class);
    }

    public function jamaah()
    {
        return $this->hasMany(Jemaah::class);
    }
}
