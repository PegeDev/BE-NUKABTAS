<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Desa extends Model
{
    use HasFactory;
    use HasRoles;

    protected $fillable = [
        'nama',
        "kecamatan_id"
    ];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }
}
