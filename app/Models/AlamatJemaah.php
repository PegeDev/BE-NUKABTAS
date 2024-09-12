<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlamatJemaah extends Model
{
    use HasFactory;


    protected $fillable = [
        'provinsi',
        'kota',
        'kecamatan',
        "desa",
        'jemaah_id',
    ];

    public function jemaah()
    {
        return $this->belongsTo(Jemaah::class);
    }

    public function provinsi()
    {
        return $this->belongsTo(Province::class, 'provinsi', 'code');
    }

    public function kota()
    {
        return $this->belongsTo(City::class, 'kota', 'code');
    }
    public function kecamatan()
    {
        return $this->belongsTo(District::class, 'kecamatan', 'code');
    }
    public function desa()
    {
        return $this->belongsTo(Village::class, 'desa', 'code');
    }
}
