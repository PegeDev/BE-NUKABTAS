<?php

namespace App\Models;

use App\Enums\StatusJemaah;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;

class Jemaah extends Model
{
    use HasFactory;
    use HasRoles;


    protected $fillable = [
        "nama_lengkap",
        "nama_panggilan",
        "nik",
        "telp",
        "email",
        "tempat_lahir",
        "tanggal_lahir",
        "profile_picture",
        "jenis_kelamin",
        "alamat_lengkap",
        "kepengurusan",
        "jabatan_kepengurusan",
        "status_pernikahan",
        "penghasilan",
        "pekerjaan",
        "pendidikan_terakhir",
        'mwcnu_id'
    ];


    protected $casts = [
        'tanggal_lahir' => 'datetime'
    ];

    public function detail_jemaah(): HasOne
    {
        return $this->hasOne(DetailJemaah::class);
    }


    public function alamat_jemaah(): HasOne
    {
        return $this->hasOne(AlamatJemaah::class);
    }

    public function mwcnu(): BelongsTo
    {
        return $this->belongsTo(Mwcnu::class);
    }

    public function kader(): HasOne
    {
        return $this->hasOne(Kader::class);
    }

    public function pengurus_mwcnu()
    {
        return $this->hasMany(PengurusMwcnu::class);
    }

    public function ranting_nu()
    {
        return $this->hasMany(RantingNuMwcnu::class);
    }

    public function anak_ranting()
    {
        return $this->hasMany(AnakRantingMwcnu::class);
    }


    public function lembaga()
    {
        return $this->hasMany(AnakRantingMwcnu::class);
    }

    public function banom()
    {
        return $this->hasMany(BanomMwcnu::class);
    }


    public function getKepengurusanTypeAttribute()
    {
        $tables = [
            BanomMwcnu::class => 'Banom',
            LembagaMwcnu::class => 'Lembaga',
            PengurusMwcnu::class => 'Pengurus MWC',
            RantingNuMwcnu::class => 'Ranting NU',
            AnakRantingMwcnu::class => 'Anak Ranting',
        ];

        foreach ($tables as $table => $type) {
            $pengurus = $table::query()->whereHas("surat_keputusan_mwcnu", function ($query) {
                $query->where("end_khidmat", ">", Carbon::now());
            })
                ->where('jemaah_id', $this->id)->first();

            if ($pengurus) {
                $pengurus->type = $type;
                $pengurus->nama_kecamatan = $this->mwcnu->nama_kecamatan;
                if (in_array($type, ["Ranting NU", "Anak Ranting"])) {
                    $pengurus->nama_desa = Village::find($pengurus->village_id)->name;
                }
                return $pengurus;
            }
        }
        return (object) [
            "type" => "WARGA",
            "posisi" => null,
            "jabatan" => null,
        ];
    }


    public function getHistoryPengurusAttribute()
    {
        $tables = [
            BanomMwcnu::class => 'Banom',
            LembagaMwcnu::class => 'Lembaga',
            PengurusMwcnu::class => 'Pengurus MWC',
            RantingNuMwcnu::class => 'Ranting NU',
            AnakRantingMwcnu::class => 'Anak Ranting',
        ];

        $data = collect(); // Pindahkan deklarasi koleksi ke luar loop

        foreach ($tables as $table => $type) {
            // Ambil pengurus dari tabel
            $pengurus = $table::query()->where('jemaah_id', $this->id)->get();

            // Cek apakah ada pengurus yang ditemukan
            foreach ($pengurus as $item) {
                $item->type = $type; // Set type
                $item->nama_kecamatan = $this->mwcnu->nama_kecamatan;
                $item->masa_khidmat = collect([
                    "start_khidmat" => $item->surat_keputusan_mwcnu->start_khidmat,
                    "end_khidmat" => $item->surat_keputusan_mwcnu->end_khidmat
                ]);

                // Jika type adalah "Ranting NU" atau "Anak Ranting", ambil nama desa
                if ($type === "Ranting NU" || $type === "Anak Ranting") {
                    $village = Village::find($item->village_id);
                    $item->nama_desa = $village ? $village->name : null; // Cek jika village ditemukan
                }

                $data->push($item);
            }
        }

        return $data; // Kembalikan koleksi data
    }
}
