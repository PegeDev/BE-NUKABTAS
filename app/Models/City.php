<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravolt\Indonesia\Models\City as ModelsCity;

class City extends ModelsCity
{
    use HasFactory;

    public function alamat_jemaah(): HasOne
    {
        return $this->hasOne(AlamatJemaah::class, "kota", "code");
    }
}
