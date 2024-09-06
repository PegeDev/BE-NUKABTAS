<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravolt\Indonesia\Models\City as ModelsCity;

class City extends ModelsCity
{
    use HasFactory;

    public function alamat_jemaah(): HasMany
    {
        return $this->hasMany(AlamatJemaah::class, "kota");
    }
}
