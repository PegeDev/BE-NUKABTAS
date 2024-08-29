<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengurusMwcnu extends Model
{
    use HasFactory;

    protected $fillable = [
        'jabatan',
        'jemaah_id',
        'mwcnu_id',
    ];


    public function mwcnu()
    {
        return $this->belongsTo(Mwcnu::class);
    }

    public function jamaah()
    {
        return $this->belongsTo(Jemaah::class);
    }
}
