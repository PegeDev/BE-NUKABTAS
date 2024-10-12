<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kader extends Model
{
    use HasFactory;

    protected $fillable = [
        'jemaah_id',
        'kaderisasi',
    ];

    public function jemaah(): BelongsTo
    {
        return $this->belongsTo(Jemaah::class);
    }
}
