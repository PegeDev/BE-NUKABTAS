<?php

namespace App\Models;

use App\Enums\StatusJemaah;
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
        "jenis_kelamin",
        "profile_picture",
        "alamat_lengkap",
        "kepengurusan",
        "jabatan_kepengurusan",
        "status_pernikahan",
        "penghasilan",
        "pekerjaan",
        "pendidikan_terakhir",
        'mwcnu_id'
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


    public function getKepengurusanTypeAttribute()
    {
        $tables = [
            'banom_mwcnus' => 'Banom',
            'lembaga_mwcnus' => 'Lembaga',
            'pengurus_mwcnus' => 'Pengurus MWC',
            'ranting_nu_mwcnus' => 'Ranting',
            'anak_ranting_mwcnus' => 'Anak Ranting',
        ];

        foreach ($tables as $table => $type) {
            // Ambil satu hasil saja dengan first()
            $pengurus = DB::table($table)->where('jemaah_id', $this->id)->first();

            // Jika hasil ditemukan, tambahkan atribut baru
            if ($pengurus) {
                $pengurus->type = $type; // Menambahkan type dari $type
                if ($type === "Ranting" || $type === "Anak Ranting") {
                    $pengurus->nama_desa = Village::find($pengurus->village_id)->name;
                }
                return $pengurus; // Kembalikan objek dengan atribut tambahan
            }
        }
        return 'Warga';
    }
}
