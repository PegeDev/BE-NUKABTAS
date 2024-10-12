<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
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
    public function pengurus_mwcnu(): HasManyThrough
    {
        return $this->hasManyThrough(PengurusMwcnu::class, SuratKeputusanMwcnu::class)
            ->where("end_khidmat", ">", Carbon::now());
    }

    public function ranting_nu(): HasManyThrough
    {
        return $this->hasManyThrough(RantingNuMwcnu::class, SuratKeputusanMwcnu::class)
            ->where("end_khidmat", ">", Carbon::now());
    }

    public function anak_ranting(): HasManyThrough
    {
        return $this->hasManyThrough(AnakRantingMwcnu::class, SuratKeputusanMwcnu::class)
            ->where("end_khidmat", ">", Carbon::now());
    }

    public function lembaga_mwcnu(): HasManyThrough
    {
        return $this->hasManyThrough(LembagaMwcnu::class, SuratKeputusanMwcnu::class)
            ->where("end_khidmat", ">", Carbon::now());
    }

    public function banom_mwcnu(): HasManyThrough
    {
        return $this->hasManyThrough(BanomMwcnu::class, SuratKeputusanMwcnu::class)
            ->where("end_khidmat", ">", Carbon::now());
    }

    public function all_pengurus($relation = null)
    {

        $pengurusMwcnu = $this->pengurus_mwcnu()
            ->where("end_khidmat", ">", Carbon::now());
        $rantingNu = $this->ranting_nu()
            ->where("end_khidmat", ">", Carbon::now());
        $anakRanting = $this->anak_ranting()
            ->where("end_khidmat", ">", Carbon::now());
        $lembaga = $this->lembaga_mwcnu()
            ->where("end_khidmat", ">", Carbon::now());
        $banom = $this->banom_mwcnu()
            ->where("end_khidmat", ">", Carbon::now());


        if ($relation === 'pengurus_mwc') {
            return $pengurusMwcnu
                ->select("pengurus_mwcnus.id", "pengurus_mwcnus.jemaah_id", "pengurus_mwcnus.posisi", "pengurus_mwcnus.jabatan");
        } elseif ($relation === 'ranting_nu') {
            return  $rantingNu
                ->select("ranting_nu_mwcnus.id", "ranting_nu_mwcnus.jemaah_id", "ranting_nu_mwcnus.posisi", "ranting_nu_mwcnus.jabatan", "ranting_nu_mwcnus.village_id");
        } elseif ($relation === 'anak_ranting') {
            return $anakRanting
                ->select("anak_ranting_mwcnus.id", "anak_ranting_mwcnus.jemaah_id", "ranting_nu_mwcnus.posisi", "ranting_nu_mwcnus.jabatan");
        } elseif ($relation === 'lembaga') {
            return $lembaga
                ->select("lembaga_mwcnus.id", "lembaga_mwcnus.jemaah_id", "lembaga_mwcnus.posisi", "lembaga_mwcnus.jabatan");
        } elseif ($relation === 'banom') {
            return $banom
                ->select("banom_mwcnus.id", "banom_mwcnus.jemaah_id", "banom_mwcnus.posisi", "banom_mwcnus.jabatan");
        }

        return ($pengurusMwcnu
            ->select("pengurus_mwcnus.id", "pengurus_mwcnus.jemaah_id")
            ->orderByRaw("NULL"))
            ->unionAll($rantingNu->select("ranting_nu_mwcnus.id", "ranting_nu_mwcnus.jemaah_id"))
            ->unionAll($anakRanting->select("anak_ranting_mwcnus.id", "anak_ranting_mwcnus.jemaah_id"))
            ->unionAll($lembaga->select("lembaga_mwcnus.id", "lembaga_mwcnus.jemaah_id"))
            ->unionAll($banom->select("banom_mwcnus.id", "banom_mwcnus.jemaah_id"));
    }

    public function getPengurusSkAttribute()
    {
        return (object) [
            "sk_pengurus" => $this->surat_keputusan_mwcnu()
                ->where("end_khidmat", ">", Carbon::now())
                ->where("jenis_kepengurusan", "pengurus_mwc")->first(),
            "sk_ranting_nu" => $this->surat_keputusan_mwcnu()
                ->where("end_khidmat", ">", Carbon::now())
                ->where("jenis_kepengurusan", "ranting_nu")->first(),
            "sk_anak_ranting" => $this->surat_keputusan_mwcnu()
                ->where("end_khidmat", ">", Carbon::now())
                ->where("jenis_kepengurusan", "anak_ranting")->first(),
            "sk_lembaga" => $this->surat_keputusan_mwcnu()
                ->where("end_khidmat", ">", Carbon::now())
                ->where("jenis_kepengurusan", "lembaga")->first(),
            "sk_banom" => $this->surat_keputusan_mwcnu()
                ->where("end_khidmat", ">", Carbon::now())
                ->where("jenis_kepengurusan", "banom")->first(),

        ];
    }
}
