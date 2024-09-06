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
        return $this->belongsTo(Province::class);
    }

    public function kota()
    {
        return $this->belongsTo(City::class);
    }
    public function kecamatan()
    {
        return $this->belongsTo(District::class);
    }
    public function desa()
    {
        return $this->belongsTo(Village::class);
    }
}
