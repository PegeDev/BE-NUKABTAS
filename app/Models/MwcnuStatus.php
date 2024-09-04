<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MwcnuStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        "status",
        "message",
        "mwcnu_id"
    ];

    public function mwcnu()
    {
        return $this->belongsTo(Mwcnu::class);
    }
}
