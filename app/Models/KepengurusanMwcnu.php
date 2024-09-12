<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KepengurusanMwcnu extends Model
{
    use HasFactory;


    protected $fillable = [
        'mwcnu_id',
    ];


    public function mwcnu(): BelongsTo
    {
        return $this->belongsTo(Mwcnu::class);
    }

    public function pengurus_mwcnu(): HasMany
    {
        return $this->hasMany(PengurusMwcnu::class);
    }

    public function lembaga_mwcnu(): HasMany
    {
        return $this->hasMany(LembagaMwcnu::class);
    }

    public function banom_mwcnu(): HasMany
    {
        return $this->hasMany(BanomMwcnu::class);
    }

    public function ranting_nu(): HasMany
    {
        return $this->hasMany(RantingNuMwcnu::class);
    }

    public function anak_ranting(): HasMany
    {
        return $this->hasMany(AnakRantingMwcnu::class);
    }
}
