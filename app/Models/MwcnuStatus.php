<?php

namespace App\Models;

use App\Enums\MwcnuStatus as EnumsMwcnuStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MwcnuStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        "status",
        "message",
        "mwcnu_id",
    ];

    protected $casts = [
        "status" => EnumsMwcnuStatus::class
    ];

    public function mwcnu()
    {
        return $this->belongsTo(Mwcnu::class);
    }
}
