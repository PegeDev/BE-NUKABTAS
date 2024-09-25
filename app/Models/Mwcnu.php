<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Permission\Traits\HasRoles;

class Mwcnu extends Model
{
    use HasFactory;
    use HasRoles;


    protected $fillable = [
        "nama_kecamatan"
    ];

    public function jemaahs(): HasMany
    {
        return $this->hasMany(Jemaah::class);
    }


    public function kepengurusan(): HasOne
    {
        return $this->hasOne(KepengurusanMwcnu::class, "mwcnu_id");
    }

    public function form_mwcnu(): HasOne
    {
        return $this->hasOne(FormMwcnu::class);
    }

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class, "admin_id");
    }

    public function detail_mwcnus(): HasOne
    {
        return $this->hasOne(DetailMwcnu::class);
    }

    public function status(): HasMany
    {
        return $this->hasMany(MwcnuStatus::class);
    }

    public function current_status()
    {
        return $this->hasOne(MwcnuStatus::class)->latestOfMany();
    }

    public function pengajuan_sk_mwcnu(): HasMany
    {
        return $this->hasMany(PengajuanSkMwcnu::class);
    }
    public function surat_keputusan_mwcnu(): HasMany
    {
        return $this->hasMany(SuratKeputusanMwcnu::class);
    }
}
