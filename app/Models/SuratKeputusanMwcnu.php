<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class SuratKeputusanMwcnu extends Model
{
    use HasFactory;

    protected $fillable = [
        "nomor_surat",
        "start_khidmat",
        "end_khidmat",
        "file_surat",
        "jenis_kepengurusan",
        "mwcnu_id",
    ];

    public function mwcnu(): BelongsTo
    {
        return $this->belongsTo(Mwcnu::class);
    }

    public function pengurus_mwcnu(): HasMany
    {
        return $this->hasMany(PengurusMwcnu::class, "surat_keputusan_mwcnu_id");
    }

    public function lembaga_mwcnu(): HasMany
    {
        return $this->hasMany(LembagaMwcnu::class, "surat_keputusan_mwcnu_id");
    }

    public function banom_mwcnu(): HasMany
    {
        return $this->hasMany(BanomMwcnu::class, "surat_keputusan_mwcnu_id");
    }

    public function ranting_nu(): HasMany
    {
        return $this->hasMany(RantingNuMwcnu::class, "surat_keputusan_mwcnu_id");
    }

    public function anak_ranting(): HasMany
    {
        return $this->hasMany(AnakRantingMwcnu::class, "surat_keputusan_mwcnu_id");
    }

    public function all_pengurus()
    {
        $pengurusMwc = $this->pengurus_mwcnu()
            ->select("id", "jemaah_id")
            ->orderBy(DB::raw("NULL"));
        $lembaga = $this->lembaga_mwcnu()
            ->select("id", "jemaah_id");
        $banom = $this->banom_mwcnu()
            ->select("id", "jemaah_id");
        $ranting = $this->ranting_nu()
            ->select("id", "jemaah_id");
        $anakRanting = $this->anak_ranting()
            ->select("id", "jemaah_id");

        return $pengurusMwc
            ->union($lembaga)
            ->union($banom)
            ->union($ranting)
            ->union($anakRanting);
    }
}
