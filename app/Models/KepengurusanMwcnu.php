<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

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
        return $this->hasMany(PengurusMwcnu::class, "kepengurusan_id");
    }

    public function lembaga_mwcnu(): HasMany
    {
        return $this->hasMany(LembagaMwcnu::class, "kepengurusan_id");
    }

    public function banom_mwcnu(): HasMany
    {
        return $this->hasMany(BanomMwcnu::class, "kepengurusan_id");
    }

    public function ranting_nu(): HasMany
    {
        return $this->hasMany(RantingNuMwcnu::class, "kepengurusan_id");
    }

    public function anak_ranting(): HasMany
    {
        return $this->hasMany(AnakRantingMwcnu::class, "kepengurusan_id");
    }

    public function all_pengurus()
    {
        $tables = [
            'banom_mwcnus' => "Banom",
            'lembaga_mwcnus' => "Lembaga",
            'pengurus_mwcnus' => "Pengurus",
            'ranting_nu_mwcnus' => "Ranting Nu",
            'anak_ranting_mwcnus' => "Anak Ranting",
        ];

        $jemaahQuery = Jemaah::query();

        foreach ($tables as $table => $role) {
            $jemaahQuery->orWhereIn('id', function ($query) use ($table, $role) {
                $query->select('jemaah_id')
                    ->from($table)
                    ->where('kepengurusan_id', $this->id);
            });
        }
        return $jemaahQuery;
    }
}
