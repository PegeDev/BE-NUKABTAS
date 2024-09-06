<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravolt\Indonesia\Models\District as ModelsDistrict;

class District extends ModelsDistrict
{
    use HasFactory;

    public function alamat_jemaah(): HasMany
    {
        return $this->hasMany(AlamatJemaah::class, "kecamatan");
    }
}
